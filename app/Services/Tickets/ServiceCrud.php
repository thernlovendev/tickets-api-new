<?php

namespace App\Services\Tickets;
use App\Models\Ticket;
use App\Models\Company;
use App\Models\Subcategory;
use App\Models\TicketPrice;
use App\Models\TicketSchedule;
use App\Models\TicketContent;
use DB;
use Validator;
use App\Utils\ModelCrud;
use Illuminate\Validation\Rule;
use App\Services\Images\Service as ImageService;

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

            $ticket = Ticket::create(
                [
                    'company_id' => $data['company_id'],
                    'city_id' => $data['city_id'],
                    'title_en' => $data['title_en'],
                    'title_kr' => $data['title_kr'],
                    'ticket_template' => $data['ticket_template'],
                    'ticket_type' => $data['ticket_type'],
                    'status' => $data['status'],
                    'out_of_stock_alert_adult' => $data['out_of_stock_alert_adult'],
                    'out_of_stock_alert_child' => $data['out_of_stock_alert_child'],
                    'currency' => $data['currency'],
                    'product_code' => $code,
                    'additional_price_type' => $data['additional_price_type'],
                    'additional_price_amount' => $data['additional_price_amount'],
                    'show_in_schedule_page' => $data['show_in_schedule_page'],
                    'announcement' => $data['announcement']
                ]);

            TicketContent::create(['ticket_id' => $ticket->id,'content' => $data['ticket_content']['content']]); 

            foreach($data['tickets_categories'] as $category){
                $ticket->categories()->attach($category['category_id']);
            }

            foreach($data['tickets_subcategories'] as $subcategory){
                $ticket->subcategories()->attach($subcategory['subcategory_id']);
            }

            foreach ($data['tickets_prices'] as $price) {
                $item = TicketPrice::create(['ticket_id'=> $ticket['id'],'type' => $price['type'], 'age_limit' => $price['age_limit'], 'window_price' => $price['window_price'], 'sale_price' => $price['sale_price']]); 
                
            }
            
            

            if($data['show_in_schedule_page'] == true){
                foreach ($data['tickets_schedule'] as $schedule) {
                    $schedule['ticket_id'] = $ticket['id'];

                    TicketSchedule::create($schedule); 
                    
                }
            }
            foreach($data['wide_images'] as $image){
                ImageService::attach($image, $ticket);
            }

            foreach($data['gallery_images'] as $image){
                ImageService::attach($image, $ticket);
            }

            ImageService::attach($data['card_image'], $ticket);
            
                
            DB::commit();

            return $ticket->load('categories', 'subcategories', 'ticketPrices', 'ticketContent', 'ticketSchedules', 'wideImages', 'galleryImages', 'cardImage',
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
            ModelCrud::deleteUpdateOrCreate($ticket->ticketPrices(), $data['tickets_prices']);
            ModelCrud::deleteUpdateOrCreate($ticket->ticketSchedules(), $data['tickets_schedule']);

            $categories = collect($data['tickets_categories'])->pluck('category_id');
            $ticket->categories()->sync($categories);

            $subcategories = collect($data['tickets_subcategories'])->pluck('subcategory_id');
            $ticket->subcategories()->sync($subcategories);

            $ticket_content = $ticket->ticketContent()->first();
            $ticket_content['content'] = $data['ticket_content']['content'];
            $ticket_content->save();
            
            $card_image = collect($data['card_image']);

            if($ticket->cardImage->id !== $data['card_image']['id']){
                $ticket->cardImage->delete();
                ImageService::attach($card_image, $ticket);
            } 

            $wide_images = collect($data['wide_images']);

            $wide_image_old = $ticket->wideImages()->pluck('id');

            $wide_image_request = collect($wide_images)->whereNotNull('id')->pluck('id');
            
            $wide_images_to_delete = $wide_image_old->diff($wide_image_request)->all();
            
            //delete images gone

            foreach($wide_images_to_delete as $wide_image_id) {
                $ticket->wideImages()->where('id', $wide_image_id)->delete();
            }

            //Create or update
            foreach ($data['wide_images'] as $wide_image) {
                $wide_image_update = $ticket->wideImages()->find($wide_image['id']);
                if($wide_image_update){
                    $wide_image_update->update([
                        'priority' => $wide_image['priority'],
                        'priority_type' => $wide_image['priority_type'],
                    ]); 
                } else {
                    ImageService::attach($wide_image, $ticket);
                }
            }

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
            return $ticket->load('categories', 'subcategories','ticketPrices', 'ticketContent', 'ticketSchedules', 'wideImages', 'galleryImages', 'cardImage');

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