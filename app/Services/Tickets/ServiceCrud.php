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

            $company = Company::find($data->company_id);

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
                    'company_id' => $data->company_id,
                    'city_id' => $data->city_id,
                    'title_en' => $data->title_en,
                    'title_kr' => $data->title_kr,
                    'ticket_template' => $data->ticket_template,
                    'ticket_type' => $data->ticket_type,
                    'status' => $data->status,
                    'out_of_stock_alert' => $data->out_of_stock_alert,
                    'currency' => $data->currency,
                    'product_code' => $code,
                    'additional_price_type' => $data->additional_price_type,
                    'additional_price_amount' => $data->additional_price_amount,
                    'show_in_schedule_page' => $data->show_in_schedule_page,
                    'announcement' =>$data->announcement,
                ]);

            foreach($data->tickets_categories as $category){
                $ticket->categories()->attach($category['category_id']);
            }

            foreach($data->tickets_subcategories as $subcategory){
                $ticket->subcategories()->attach($subcategory['subcategory_id']);
            }
            

            $prices_counter = [];

            foreach ($data->tickets_prices as $price) {
                $item = TicketPrice::create(['ticket_id'=> $ticket->id,'type' => $price['type'], 'age_limit' => $price['age_limit'], 'window_price' => $price['window_price'], 'sale_price' => $price['sale_price']]); 
                
                $prices_counter[] = $item;
            }
            
            $prices['prices'] = $prices_counter;
            
            $coments_counter = [];
            
            foreach ($data->tickets_content as $article) {
                $item = TicketContent::create(['ticket_id'=> $ticket->id,'name' => $article['name'], 'content' => $article['content']]); 
            
                $coments_counter[] = $item;
            }

            $contents['contents'] = $coments_counter;


            $schedule_counter = [];
            if($data->show_in_schedule_page == true){
                foreach ($data->tickets_schedule as $schedule) {
                    
                    $item = TicketSchedule::create(['ticket_id'=> $ticket->id,'date_start' => $schedule['date_start'], 'date_end' => $schedule['date_end'], 'max_people' => $schedule['max_people'], 'week_days' => collect($schedule['week_days'])]); 
                    
                    $schedule_counter[] = $item;
                }
            }
            $schedules['schedule'] = $schedule_counter;

            foreach($data->wide_images as $image){
                ImageService::attach($image, $ticket);
            }

            foreach($data->gallery_images as $image){
                ImageService::attach($image, $ticket);
            }

            ImageService::attach($data->card_image, $ticket);
            
                
            DB::commit();

            return [$ticket, $prices, $contents, $schedules];

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $ticket)
	{
		try{
            DB::beginTransaction();

            $ticket->update([
                'name' => $data['name']
            ]); 

            ModelCrud::deleteUpdateOrCreate($ticket->subcategories(), $data['subcategories']);

            DB::commit();
            return $data;

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