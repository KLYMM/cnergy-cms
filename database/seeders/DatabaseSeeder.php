<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FrontEndMenu;
use App\Models\User;
use App\Models\News;
use App\Models\PhotoNews;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use App\Models\VideoNews;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // News::create([
        //     'title' => "Gempa di wilayah jawa barat sebesar 4.6 SR",
        //     'slug' => "article 1",
        //     'content' => "Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aliquid quasi perferendis debitis, a et id
        //                 non,
        //                 perspiciatis deserunt harum vero, voluptatibus delectus ex similique qui ipsa odit deleniti
        //                 molestias.
        //                 Nisi
        //                 veritatis libero tenetur dolore? Deserunt omnis libero inventore fugit dolore voluptas! Quo veniam
        //                 eius
        //                 explicabo, recusandae ea rem! Consequuntur iure magni quam consectetur obcaecati illo labore sit
        //                 neque
        //                 ratione
        //                 minima, vero eum a et corporis quis voluptatibus maxime recusandae quos sint voluptates dicta
        //                 laboriosam
        //                 aperiam. Rem necessitatibus provident saepe deserunt minus quaerat adipisci iure maiores amet nihil
        //                 impedit
        //                 error, voluptates aperiam dicta iste quae repellendus quam porro aspernatur ratione qui?
        //                 Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa temporibus assumenda, suscipit nihil
        //                 saepe
        //                 esse
        //                 eius laboriosam deserunt placeat quibusdam consectetur ab. Assumenda exercitationem veniam animi
        //                 iusto
        //                 repudiandae, enim quis eligendi natus cupiditate aliquam accusantium omnis quibusdam sequi vitae
        //                 doloremque
        //                 adipisci ullam sed fuga iure, nesciunt, ducimus temporibus sunt. Porro architecto consequuntur eius
        //                 modi
        //                 quisquam obcaecati culpa iste, fugit itaque hic. Non doloremque molestias alias repudiandae commodi
        //                 magni
        //                 sint
        //                 culpa eum nemo voluptates libero eligendi, eos fugit itaque reiciendis veniam nam consequatur?
        //                 Dolorem
        //                 unde
        //                 quidem, obcaecati delectus assumenda porro rerum optio, explicabo voluptas dolore cupiditate autem
        //                 fugiat
        //                 iure
        //                 excepturi, distinctio nobis. Nam exercitationem doloremque iste at alias atque sequi aspernatur nisi
        //                 qui,
        //                 dolores similique ex ad. Quis dicta voluptates impedit. Voluptatum natus velit tenetur nisi, odio
        //                 dolorum.",
        //     'synopsis' => "sinposis 1",
        //     'description' => "gempa jawab barat",
        //     'created_by' => "Oky",
        // ]);

        Category::create([
            'category' => "berita",
            'common' => "common test",
            'slug' => "bertia slug1",
            'types' => "berita",
            'created_by' => "Mr. Titus Williamson"
        ]);


        // $this->call([
        //     RoleSeeder::class,
        //     MenuSeeder::class,
        //     FrontEndMenuSeeder::class,

        // ]);
        // User::factory(100)->create();
    }
}
