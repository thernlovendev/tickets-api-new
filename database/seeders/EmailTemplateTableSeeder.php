<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titles = ['After Signed Up','After Password Reset','After Password Reset Request By User','After Tickets Uploaded By Admin','After Upgraded Order','After Payment Completed','After Account Deleted'];

        foreach($titles as $title){
            Template::updateOrCreate(['title' => $title,'type' => 'Email'],
            ['title' => $title, 'type' => 'Email','header_gallery_id' => null, 'content' => 'default', 'status' => Template::STATUS['PUBLISH'],'created_by' => "Admin", 'Subject'=> 'default']);
        }
    }
}
