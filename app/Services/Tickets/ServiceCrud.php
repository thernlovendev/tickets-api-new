<?php

namespace App\Services\Tickets;
use App\Models\Ticket;
use App\Models\Company;
use App\Models\Subcategory;
use App\Models\TicketPrice;
use App\Models\TicketSchedule;
use App\Models\TicketContent;
use App\Models\TicketScheduleException;
use DB;
use Validator;
use App\Utils\ModelCrud;
use Illuminate\Validation\Rule;
use App\Services\Images\Service as ImageService;
use Carbon\Carbon;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $company = Company::find($data['company_id']);

            $words = explode(" ", $company->name);
            $prefix = "";
            
            foreach ($words as $w) {
              $prefix .= mb_substr($w, 0, 1);
            }

            do {
                $number_code =  mt_rand(1000000, 9999999);
                settype($number_code, 'string');
                $code = $prefix.$number_code;
            } while (Ticket::where("product_code", "=", $code)->exists());

            $last_ticket_order = Ticket::where('company_id', $data['company_id'])->orderByDesc('order')->first();

            $order = $last_ticket_order ? $last_ticket_order->order + 1 : 1;
            $isBigApplePass = false;
            if(isset($data['tickets_subcategories']) && !empty($data['tickets_subcategories'])){
                $subcategories_count = Subcategory::whereIn('id', $data['tickets_subcategories'])->get();
                $hasPremiumPrices = collect($subcategories_count)->contains('allow_premium_prices', true);

                if ($hasPremiumPrices) {
                    $isBigApplePass = true;
                } else {
                    $isBigApplePass = false;
                }
            }

            $ticket = Ticket::create(
                [
                    'company_id' => $data['company_id'],
                    'city_id' => $data['city_id'],
                    'title_en' => $data['title_en'],
                    'title_kr' => $data['title_kr'],
                    'template_id' => $data['template_id'],
                    'ticket_type' => $data['ticket_type'],
                    'status' => $data['status'],
                    'out_of_stock_alert_adult' => $data['out_of_stock_alert_adult'],
                    'out_of_stock_alert_child' => $data['out_of_stock_alert_child'],
                    'currency' => $data['currency'],
                    'product_code' => $code,
                    'additional_price_type' => $isBigApplePass ? $data['additional_price_type'] : Ticket::ADDITIONAL_PRICE_TYPE['NONE'],
                    'additional_price_amount' => $isBigApplePass ? $data['additional_price_amount'] : 0,
                    'premium_amount' => $isBigApplePass ? $data['premium_amount'] : 0,
                    'premium_s_amount' => $isBigApplePass ? $data['premium_s_amount'] : 0,
                    'show_in_schedule_page' => $data['show_in_schedule_page'],
                    'announcement' =>$data['announcement'],
                    'order' => $order
                ]);

            TicketContent::create(['ticket_id' => $ticket->id,'content' => $data['ticket_content']['content']]); 

            if(isset($data['tickets_categories']) && !empty($data['tickets_categories'])){
                foreach($data['tickets_categories'] as $category){
                    $ticket->categories()->attach($category['category_id']);
                }
            }
            
            if(isset($data['tickets_subcategories']) && !empty($data['tickets_subcategories'])){
                foreach($data['tickets_subcategories'] as $subcategory){
                    $ticket->subcategories()->attach($subcategory['subcategory_id']);
                }
            }

            if(isset($data['tickets_prices']) && !empty($data['tickets_prices'])){
                foreach ($data['tickets_prices'] as $price) {
                    $item = TicketPrice::create(['ticket_id'=> $ticket['id'],'type' => $price['type'], 'age_limit' => $price['age_limit'], 'window_price' => $price['window_price'], 'sale_price' => $price['sale_price']]); 
                    
                }
            }
            
            // if(isset($data['tickets_content'])){
            //     foreach ($data['tickets_content'] as $article) {
            //         $item = TicketContent::create(['ticket_id'=> $ticket['id'],'name' => $article['name'], 'content' => $article['content']]); 
            //     }
            // }

            if($data['show_in_schedule_page'] == true){
                foreach ($data['tickets_schedule'] as $schedule) {
                    $schedule['ticket_id'] = $ticket['id'];

                    $ticket_schedule = TicketSchedule::create($schedule); 

                    if(isset($schedule['ticket_schedule_exceptions'])){
                        $schedule_exceptions = collect($schedule['ticket_schedule_exceptions'])
                            ->map( function($item) use ($ticket_schedule) {
                                return [
                                    'date' => $item['date'],
                                    'max_people' => $item['max_people'],
                                    'time' => $ticket_schedule->time,
                                    'day' => Carbon::parse($item['date'])->format('l'),
                                    'show_on_calendar' => $item['show_on_calendar']
                                ];
                        });

                        $schedule_exception = $ticket_schedule->ticketScheduleExceptions()->createMany($schedule_exceptions);
                    }
                    
                }
            }
            // if(isset($data['wide_images']) && !empty($data['wide_images'])){
            //     foreach($data['wide_images'] as $image){
            //         ImageService::attach($image, $ticket);
            //     }
            // }
            if(isset($data['gallery_images']) && !empty($data['gallery_images'])){
                foreach($data['gallery_images'] as $image){
                    ImageService::attach($image, $ticket);
                }
            }
            if(isset($data['card_image']) && !empty($data['card_image']) && isset($data['card_image']['id'])){
                ImageService::attach($data['card_image'], $ticket);
            }        
            
            if(isset($data['icon_image']) && !empty($data['icon_image']) && isset($data['icon_image']['id'])){
                ImageService::attach($data['icon_image'], $ticket);
            }        
                
            DB::commit();

            return $ticket->load('categories', 'subcategories', 'ticketPrices', 'ticketContent', 'ticketSchedules', 'galleryImages', 'cardImage','iconImage'
            );

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $ticket)
	{
		try{
            DB::beginTransaction();

            $ticket->update($data);
            // ModelCrud::deleteUpdateOrCreate($ticket->ticketContents(), $data['tickets_content']);
            ModelCrud::deleteUpdateOrCreate($ticket->ticketPrices(), $data['tickets_prices']);
            ModelCrud::deleteUpdateOrCreate($ticket->ticketSchedules(), $data['tickets_schedule']);

            $categories = collect($data['tickets_categories'])->pluck('category_id');
            $ticket->categories()->sync($categories);

            $subcategories = collect($data['tickets_subcategories'])->pluck('subcategory_id');
            $ticket->subcategories()->sync($subcategories);

            $ticket_content = $ticket->ticketContent()->first();
            
            if($ticket_content === null){
                TicketContent::create([
                    'ticket_id' => $ticket->id,
                    'content' => $data['ticket_content']['content']
                ]);
            } else {
                $ticket_content->update([
                    'content' => $data['ticket_content']['content']
                ]);

                $ticket_content->save();
            };
            
            $card_image = collect($data['card_image']);
            
            if($ticket->cardImage->id !== $data['card_image']['id']){
                $ticket->cardImage->delete();
                ImageService::attach($card_image, $ticket);
            } 

            if($data['icon_image']['id'] !== null && $ticket->iconImage->id !== $data['icon_image']['id']){
                
                if($ticket->iconImage !== null){
                    $ticket->iconImage->delete();
                }
                
                ImageService::attach($icon_image, $ticket);
            } 

            // $wide_images = collect($data['wide_images']);

            // $wide_image_old = $ticket->wideImages()->pluck('id');

            // $wide_image_request = collect($wide_images)->whereNotNull('id')->pluck('id');
            
            // $wide_images_to_delete = $wide_image_old->diff($wide_image_request)->all();
            
            // //delete images gone

            // foreach($wide_images_to_delete as $wide_image_id) {
            //     $ticket->wideImages()->where('id', $wide_image_id)->delete();
            // }

            // //Create or update
            // foreach ($data['wide_images'] as $wide_image) {
            //     $wide_image_update = $ticket->wideImages()->find($wide_image['id']);
            //     if($wide_image_update){
            //         $wide_image_update->update([
            //             'priority' => $wide_image['priority'],
            //             'priority_type' => $wide_image['priority_type'],
            //         ]); 
            //     } else {
            //         ImageService::attach($wide_image, $ticket);
            //     }
            // }

            $gallery_images = collect($data['gallery_images']);

            $gallery_image_old = $ticket->galleryImages()->pluck('id');

            $gallery_image_request = collect($gallery_images)->whereNotNull('id')->pluck('id');
            
            $gallery_images_to_delete = $gallery_image_old->diff($gallery_image_request)->all();
            
            //delete images gone

            foreach($gallery_images_to_delete as $gallery_image_id) {
                $ticket->galleryImages()->where('id', $gallery_image_id)->delete();
            }

            //Create or update
            foreach ($data['gallery_images'] as $gallery_image) {
                $gallery_image_update = $ticket->galleryImages()->find($gallery_image['id']);
                if($gallery_image_update){
                    $gallery_image_update->update([
                        'priority' => $gallery_image['priority'],
                        'priority_type' => $gallery_image['priority_type'],
                    ]); 
                } else {
                    ImageService::attach($gallery_image, $ticket);
                }
            }

            DB::commit();
            return $ticket->load('categories', 'subcategories','ticketPrices', 'ticketContent', 'ticketSchedules', 'iconImage', 'galleryImages', 'cardImage');

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($ticket)
	{
        // $ticket->delete();
        // return $ticket;
    }

    public static function response($ticket)
    {
        return $ticket;
    }
}