<?php

namespace Database\Seeders;

use App\Models\Article;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Str;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create articles with random categories
        foreach (range(1, 10) as $index) {

            $titleWords = [
                'The', 'Ultimate', 'Guide', 'To', 'Mastering', 'In', 'Depth',
                'Exploring', 'The Art', 'Of', 'Advanced', 'Tips', 'Tricks',
                'Unveiling', 'Secrets', 'Demystifying', 'Proven', 'Strategies',
                'Essential', 'Effective', 'Successful', 'Powerful', 'Insights',
                'Key', 'Innovative', 'Expert', 'Comprehensive', 'Practical',
                'Critical', 'Insider', 'Fundamental', 'Cutting-Edge', 'Latest',
                'Advanced', 'Advanced', 'Revolutionary', 'Simple', 'Complete',
                'Step-by-Step', 'Advanced', 'Pro', 'Secret', 'Quick',
                'Efficient', 'Innovative', 'Proven', 'Advanced', 'Strategic',
                'Quick', 'Insider', 'Time-Saving', 'Handy', 'Best',
                'Revolutionary', 'Master', 'Essential', 'Quick', 'Exclusive',
                'Effective', 'Advanced', 'Ultimate', 'Quick', 'Proven',
                'Expert', 'In-Depth', 'Simple', 'Advanced', 'Crucial',
                'Practical', 'Latest', 'Powerful', 'Essential', 'Complete',
                'Practical', 'Proven', 'Comprehensive', 'Latest', 'Powerful',
            ];

            $title = $faker->randomElement($titleWords) . ' ' . $faker->randomElement($titleWords) . ' ' . $faker->randomElement($titleWords);

            $des = "<p><strong>What is Lorem Ipsum?</strong></p><p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><figure class='image'><img src='http://fowtickets.viro.com/media/images/vqLZ6eyXQv0aIwy_1692649358.jpg'></figure><p><strong>Where does it come from?</strong></p><p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of de Finibus Bonorum et Malorum (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, Lorem ipsum dolor sit amet.., comes from a line in section 1.10.32.</p><p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from de Finibus Bonorum et Malorum by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p><p><strong>Why do we use it?</strong></p><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><strong>Where can I get some?</strong></p><p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>";

            $article = Article::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . round(1, 6000),
                'body' => $des,
                'short_description' => $faker->paragraphs(2, true),
                'views' => $faker->numberBetween(0, 1000),
                'likes' => $faker->numberBetween(0, 500),
                'dislikes' => $faker->numberBetween(0, 200),
            ]);

            // Attach random categories to articles
            $article->categories()->sync([6]);
        }
    }
}
