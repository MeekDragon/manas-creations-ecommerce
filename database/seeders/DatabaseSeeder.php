<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Seed Admin & Super Admin Users ────────
        $this->call(SuperAdminSeeder::class);

        // ── Clean database tables ────────────────
        Schema::disableForeignKeyConstraints();
        ProductVariant::truncate();
        Product::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // ── Define Categories ────────────────────
        $categoriesConfig = [
            'Caricatures' => [
                'icon' => '🎭',
                'sort_order' => 1,
                'description' => 'Trendy personalized hand-drawn cartoon caricature table stands.'
            ],
            'Cricket Trophy' => [
                'icon' => '🏆',
                'sort_order' => 2,
                'description' => 'Premium custom acrylic cricket trophies for tournaments and leagues.'
            ],
            'Mandir' => [
                'icon' => '🛕',
                'sort_order' => 3,
                'description' => 'Exquisite designer acrylic mandirs with glowing LED backlighting spaces.'
            ],
            'Name Plate' => [
                'icon' => '🏷️',
                'sort_order' => 4,
                'description' => 'Elegant customized house and office door nameplates.'
            ],
            'Corporate Gift Hampers' => [
                'icon' => '🎁',
                'sort_order' => 5,
                'description' => 'Premium luxury corporate gift hampers and customized event baskets.'
            ],
        ];

        // Seed Category Models and store their IDs
        $catMap = [];
        foreach ($categoriesConfig as $name => $cfg) {
            $cat = Category::create([
                'name' => $name,
                'icon' => $cfg['icon'],
                'sort_order' => $cfg['sort_order'],
                'description' => $cfg['description'],
                'is_active' => true,
            ]);
            $catMap[$name] = $cat->id;
        }

        // Ensure target directory exists for public products
        $destPath = storage_path('app/public/products');
        File::ensureDirectoryExists($destPath);

        // Path to local Google Drive downloaded categories
        $sourcePath = base_path('scratch/drive_download/Categories');

        // Premium AI-generated marketing dictionary for files
        $metadata = [
            // Caricatures
            'aditya birla caricature.jpg' => [
                'name' => 'Aditya Birla Corporate Caricature Standee',
                'tagline' => 'Premium personalized corporate recognition gift',
                'description' => 'Celebrate corporate milestones and employee excellence with this high-gloss acrylic standee, customized with your face and the prestigious Aditya Birla group theme. Crafted on premium 5mm crystal acrylic with a solid wooden base.',
                'base_price' => 399
            ],
            'aka journalist caricature.jpg' => [
                'name' => 'Journalist & Media Professional Caricature',
                'tagline' => 'Unique custom gift for writers and reporters',
                'description' => 'A spectacular customized caricature standee designed specifically for journalists, editors, and media professionals. Highlight their passion with high-definition vibrant printing on premium gloss acrylic.',
                'base_price' => 399
            ],
            'aradhya name caricature.jpg' => [
                'name' => 'Aradhya Personalized Typography Caricature',
                'tagline' => 'Custom kids\' name cartoon desk standee',
                'description' => 'Brighten up your child\'s study desk or bedroom with this beautiful customized typography caricature. Featuring high-gloss premium colors and a sturdy polished acrylic base.',
                'base_price' => 399
            ],
            'aryan name caricature.jpg' => [
                'name' => 'Aryan Customized Name Portrait Caricature',
                'tagline' => 'Vibrant customized name caricature for boys',
                'description' => 'A charming customized caricature standee featuring high-definition printing and premium gloss finish. Perfect desk decor or birthday gift for loved ones.',
                'base_price' => 399
            ],
            'atharv birthday caricature.jpg' => [
                'name' => 'Atharv Special Birthday Celebration Caricature',
                'tagline' => 'Personalized birthday milestone cartoon gift',
                'description' => 'Make birthdays unforgettable with this custom high-gloss caricature stand. Printed on durable 5mm gloss acrylic with vibrant colors that never fade.',
                'base_price' => 399
            ],
            'best dad in the world ii.jpg' => [
                'name' => 'Best Dad in the World Caricature - Style II',
                'tagline' => 'Heartwarming father\'s day custom cartoon display',
                'description' => 'Show your dad how much he means to you with this lovely customized caricature. Featuring a super-dad theme on premium high-definition acrylic.',
                'base_price' => 399
            ],
            'best dad in the world iii.jpg' => [
                'name' => 'Best Dad in the World Caricature - Style III',
                'tagline' => 'Premium custom desk standee for your father',
                'description' => 'A modern, sleek customized caricature featuring the ultimate \'Best Dad\' theme. Hand-crafted and UV printed on premium gloss acrylic with a polished wooden base.',
                'base_price' => 399
            ],
            'best brother in the world sonu bhaiya.png' => [
                'name' => 'Best Brother in the World - Sonu Bhaiya Caricature',
                'tagline' => 'Special brotherly love customized standee',
                'description' => 'Surprise your brother with this delightful custom caricature standee. Features high-quality printing on scratch-resistant acrylic and a robust polished stand.',
                'base_price' => 399
            ],
            'best dad in the world.jpg' => [
                'name' => 'Best Dad in the World Caricature - Classic',
                'tagline' => 'The perfect appreciation gift for your dad',
                'description' => 'Our timeless classic \'Best Dad\' customized caricature standee. Elegant design, glossy finish, and high-durability acrylic make it a cherished desk item.',
                'base_price' => 399
            ],
            'best parents caricature.jpg' => [
                'name' => 'Best Parents in the World Anniversary Caricature',
                'tagline' => 'Elegant custom gift for parents\' special milestone',
                'description' => 'Celebrate your parents\' love and commitment with this premium double-face customized caricature. Handcrafted with high-definition printing on a polished wooden stand.',
                'base_price' => 399
            ],
            'deeksha & nikhil caricature ii.jpg' => [
                'name' => 'Deeksha & Nikhil Custom Couple Caricature - Style II',
                'tagline' => 'Charming romantic custom couple standee',
                'description' => 'An adorable customized couple caricature standee. Features vibrant romantic accents and ultra-glossy finishes, making it the perfect anniversary keepsake.',
                'base_price' => 399
            ],
            'deeksha & nikhil caricature.jpg' => [
                'name' => 'Deeksha & Nikhil Custom Couple Caricature - Classic',
                'tagline' => 'Elegant personalized couple cartoon portrait',
                'description' => 'Keep your love story alive on your table with this beautiful personalized couple caricature standee. UV printed on high-density glass-look acrylic.',
                'base_price' => 399
            ],
            'doctars caricature.jpg' => [
                'name' => 'Elite Doctor & Medical Professional Caricature',
                'tagline' => 'Honoring medical heroes with custom gifts',
                'description' => 'Appreciate the doctors, nurses, and medical heroes in your life with this themed custom caricature standee. Printed on premium gloss acrylic with stethoscope detailing.',
                'base_price' => 399
            ],
            'doctor name with photo.jpg' => [
                'name' => 'Personalized Doctor Name & Photo Caricature',
                'tagline' => 'Appreciation standee with photo integration',
                'description' => 'A beautiful professional gift featuring the doctor\'s name, customized photo caricature, and clinic styling. Excellent desk decor for clinics and hospitals.',
                'base_price' => 399
            ],
            'family caricature iii.jpg' => [
                'name' => 'Premium Family Portrait Caricature - Style III',
                'tagline' => 'Warm and loving customized family standee',
                'description' => 'A beautiful multi-character caricature standee representing your family in full color and high gloss. Features superior scratch-resistant printing on heavy-duty acrylic.',
                'base_price' => 399
            ],
            'family caricature iv.jpg' => [
                'name' => 'Premium Family Portrait Caricature - Style IV',
                'tagline' => 'Grand custom family group desk standee',
                'description' => 'An exquisite group custom caricature standee, perfect for displaying your beautiful family bond. Handcrafted on heavy-gauge crystal-clear acrylic.',
                'base_price' => 399
            ],
            'forever and always.jpg' => [
                'name' => 'Forever & Always Romantic Couple Caricature',
                'tagline' => 'Elegant custom wedding / anniversary keepsake',
                'description' => 'A stunning romantic caricature standee displaying a beautiful couple with the \'Forever & Always\' engraving. Exudes premium vibes on a heavy glossy acrylic plate.',
                'base_price' => 399
            ],
            'family caricature ii.jpg' => [
                'name' => 'Family Cartoon Caricature Portrait - Style II',
                'tagline' => 'Modern family character customized stand',
                'description' => 'Make family moments special with this customized cartoon portrait standee. High gloss, vibrant colors, and durable build perfect for showcase display.',
                'base_price' => 399
            ],
            'family caricature.jpg' => [
                'name' => 'Family Cartoon Caricature Portrait - Classic',
                'tagline' => 'Classic customized family caricature standee',
                'description' => 'A delightful cartoon-style family caricature that brings fun and warmth to any room. High-resolution glossy acrylic print with a premium solid wood base.',
                'base_price' => 399
            ],
            'family iii.jpg' => [
                'name' => 'Happy Family Acrylic Caricature - Style III',
                'tagline' => 'Adorable cartoon family standee',
                'description' => 'Celebrate your household\'s love with this customized family caricature standee. Features high-definition color grading and ultra-smooth laser cuts.',
                'base_price' => 399
            ],
            'family photo.jpg' => [
                'name' => 'Custom Family Photo Caricature',
                'tagline' => 'Turn your family picture into beautiful art',
                'description' => 'A premium personalized caricature designed from your favorite family photograph. Hand-polished edges, deep color saturation, and lifetime print guarantee.',
                'base_price' => 399
            ],
            'friendship.jpg' => [
                'name' => 'Best Friends Forever Custom Caricature',
                'tagline' => 'Celebrate friendship with personalized desk decor',
                'description' => 'Remind your best friend of your special bond with this customized BFF caricature stand. Premium quality, vibrant gloss printing, and excellent build.',
                'base_price' => 399
            ],
            'happy anniversary caricature.jpg' => [
                'name' => 'Happy Anniversary Custom Couple Caricature',
                'tagline' => 'Warm and romantic anniversary milestone gift',
                'description' => 'A heartwarming anniversary customized couple caricature standee. Features anniversary theme graphics on heavy high-gloss crystal acrylic.',
                'base_price' => 399
            ],
            'happy maried life caricature.jpg' => [
                'name' => 'Happy Married Life Celebration Caricature',
                'tagline' => 'Special personalized gift for newlyweds',
                'description' => 'Celebrate new beginnings with this premium customized wedding caricature. High-definition gloss printing capturing their special moment beautifully.',
                'base_price' => 399
            ],
            'happy mothers day.jpg' => [
                'name' => 'Happy Mother\'s Day Special Caricature',
                'tagline' => 'Show your mom she is your absolute hero',
                'description' => 'Make your mother smile with this customized \'Best Mom\' caricature standee. High-grade scratchproof printing on glossy premium acrylic.',
                'base_price' => 399
            ],
            'home is wherever you are.jpg' => [
                'name' => 'Home is Wherever You Are Couple Caricature',
                'tagline' => 'Warm romantic themed couple standee',
                'description' => 'A beautiful romantic couple caricature standee with a cozy home theme. Premium high-definition printing on a durable glass-look acrylic base.',
                'base_price' => 399
            ],
            'husband wife caricature.jpg' => [
                'name' => 'Husband & Wife Custom Caricature - Classic',
                'tagline' => 'Cute romantic customized desk display',
                'description' => 'Our timeless classic custom couple caricature standee. Elegant styling and superior high-definition print quality on crystal clear acrylic.',
                'base_price' => 399
            ],
            'husband wife ii.jpg' => [
                'name' => 'Husband & Wife Custom Caricature - Style II',
                'tagline' => 'Elegant custom cartoon couple standee',
                'description' => 'A modern, stylish customized couple caricature. Beautiful colors, high-gloss surface, and a sturdy polished acrylic stand.',
                'base_price' => 399
            ],
            'husband wife iv.jpg' => [
                'name' => 'Husband & Wife Custom Caricature - Style IV',
                'tagline' => 'Modern premium couple caricature standee',
                'description' => 'Capture your marriage bond with this high-end customized caricature standee. Premium quality UV printing with deep color vibrance.',
                'base_price' => 399
            ],
            'husband wife iii.jpg' => [
                'name' => 'Husband & Wife Custom Caricature - Style III',
                'tagline' => 'Charming personalized couple table decor',
                'description' => 'A stunning customized couple caricature standee with detailed illustration and professional gloss finish. Solid wooden base included.',
                'base_price' => 399
            ],
            'lover caricature ii.jpg' => [
                'name' => 'Romantic Lover\'s Custom Caricature - Style II',
                'tagline' => 'Vibrant customized token of your love',
                'description' => 'A beautiful romantic customized caricature, perfect for Valentine\'s Day, birthdays, or special date anniversaries. Crystal-clear gloss acrylic.',
                'base_price' => 399
            ],
            'lover caricature.jpg' => [
                'name' => 'Romantic Lover\'s Custom Caricature - Classic',
                'tagline' => 'Sweet personalized romantic table stand',
                'description' => 'A lovely classic customized couple caricature standee. High-grade print and excellent gloss finish makes it a stunning addition to your bedside table.',
                'base_price' => 399
            ],
            'manas name caricature.jpg' => [
                'name' => 'Manas Personalized Acrylic Name Caricature',
                'tagline' => 'Trendy typography based customized standee',
                'description' => 'A custom name caricature standee featuring the name \'Manas\' dynamically integrated into the cartoon. Premium glossy acrylic construction.',
                'base_price' => 399
            ],
            'mom my forever sunshine.jpg' => [
                'name' => 'Mom My Forever Sunshine Caricature',
                'tagline' => 'Heartfelt customized gift for your mother',
                'description' => 'A beautiful customized caricature standee celebrating your mother. Features high-quality gloss print and a sweet, loving theme.',
                'base_price' => 399
            ],
            'prof caricature ii.jpg' => [
                'name' => 'Professional Professor & Mentor Caricature - Style II',
                'tagline' => 'A meaningful appreciation gift for educators',
                'description' => 'Thank your professor, teacher, or mentor with this beautiful professional caricature standee. Features academic theme on high-definition acrylic.',
                'base_price' => 399
            ],
            'prof caricature.jpg' => [
                'name' => 'Professional Professor & Mentor Caricature - Classic',
                'tagline' => 'Classic custom standee for teachers and guides',
                'description' => 'A stellar customized caricature honoring professors and guides. High-resolution glossy acrylic print with a premium solid wood base.',
                'base_price' => 399
            ],
            'radiologist caricature.jpg' => [
                'name' => 'Radiologist & Medical Professional Caricature',
                'tagline' => 'Unique custom profession desk standee',
                'description' => 'A custom professional caricature standee designed for radiologists and medical specialists. Printed on durable scratch-resistant crystal acrylic.',
                'base_price' => 399
            ],
            '減hivani and shubham.jpg' => [ // Handle possible encoding quirks case insensitively
                'name' => 'Shivani & Shubham Custom Couple Caricature',
                'tagline' => 'Beautiful personalized romantic standee',
                'description' => 'A romantic double-caricature standee customized with Shivani and Shubham\'s faces. High gloss, premium color depth, and robust acrylic build.',
                'base_price' => 399
            ],
            'shivani and shubham.jpg' => [
                'name' => 'Shivani & Shubham Custom Couple Caricature',
                'tagline' => 'Beautiful personalized romantic standee',
                'description' => 'A romantic double-caricature standee customized with Shivani and Shubham\'s faces. High gloss, premium color depth, and robust acrylic build.',
                'base_price' => 399
            ],
            'sonali caricature.jpg' => [
                'name' => 'Sonali Personalized Acrylic Caricature',
                'tagline' => 'Charming customized caricature standee',
                'description' => 'A personalized single-character caricature standee featuring high-definition printing and premium gloss finish. Perfect desk decor or birthday gift.',
                'base_price' => 399
            ],
            'vishal dada.png' => [
                'name' => 'Vishal Dada Custom Brother Caricature',
                'tagline' => 'A special brotherly custom cartoon standee',
                'description' => 'Surprise your elder brother with this beautiful customized caricature. High-gloss premium colors and a sturdy polished acrylic base.',
                'base_price' => 399
            ],
            'winner caricature.jpg' => [
                'name' => 'Sports Winner Custom Caricature Stand',
                'tagline' => 'A unique, fun achievement trophy',
                'description' => 'Celebrate victories and sports milestones with this customized sports winner caricature standee. High gloss printing on durable premium acrylic.',
                'base_price' => 399
            ],
            'best brother.png' => [
                'name' => 'Best Brother in the World Caricature - Classic',
                'tagline' => 'The absolute best appreciation gift for brothers',
                'description' => 'A perfect birthday gift for your brother. Featuring a handsome customized cartoon character on high-gloss acrylic with a polished wooden base.',
                'base_price' => 399
            ],
            'best teacher in the world.jpg' => [
                'name' => 'Best Teacher in the World Caricature',
                'tagline' => 'Personalized educator appreciation gift',
                'description' => 'Express your gratitude to your favorite teacher with this customized caricature standee. High-grade scratchproof printing on glossy premium acrylic.',
                'base_price' => 399
            ],
            'divya rohit caricature final.png' => [
                'name' => 'Divya & Rohit Romantic Couple Caricature',
                'tagline' => 'Charming romantic custom couple standee',
                'description' => 'An adorable customized couple caricature standee. Features vibrant romantic accents and ultra-glossy finishes, making it the perfect anniversary keepsake.',
                'base_price' => 399
            ],
            'vishal thapa family caricature final.png' => [
                'name' => 'Vishal Thapa Family Portrait Caricature',
                'tagline' => 'Exquisite custom family group desk standee',
                'description' => 'An exquisite group custom caricature standee, perfect for displaying your beautiful family bond. Handcrafted on heavy-gauge crystal-clear acrylic.',
                'base_price' => 399
            ],
            'vishal dada 2.png' => [
                'name' => 'Vishal Dada Custom Caricature - Style II',
                'tagline' => 'Customized wooden-base caricature standee',
                'description' => 'A modern, sleek customized caricature featuring your brother\'s photo. Hand-crafted and UV printed on premium gloss acrylic with a polished wooden base.',
                'base_price' => 399
            ],

            // Cricket Trophy
            'best batsman.jpg' => [
                'name' => 'Champion Best Batsman Trophy',
                'tagline' => 'Spectacular golden-accent batsman award',
                'description' => 'Honor the highest scorer of your tournament with this magnificent best batsman acrylic trophy. Features high-definition cricket graphic prints and custom lettering space.',
                'base_price' => 499
            ],
            'best batsman2.jpg' => [
                'name' => 'Elite Best Batsman Trophy - Style II',
                'tagline' => 'Modern custom batting championship award',
                'description' => 'A beautifully designed batting trophy featuring aggressive batsman silhouette on high-density glass-look acrylic. Excellent tournament award.',
                'base_price' => 499
            ],
            'best field.jpg' => [
                'name' => 'Outstanding Fielder Trophy',
                'tagline' => 'Elegant award for best fielding performance',
                'description' => 'Recognize outstanding athletic catches and fielding with this custom-cut acrylic trophy. High-grade print with sleek, polished edges.',
                'base_price' => 499
            ],
            'best fielder.jpg' => [
                'name' => 'Elite Best Fielder Trophy - Style II',
                'tagline' => 'Modern fielding achievement award',
                'description' => 'Celebrate spectacular defensive play and catches with this dynamic fielder silhouette trophy. Handcrafted on premium crystal-clear acrylic.',
                'base_price' => 499
            ],
            'caps.jpg' => [
                'name' => 'Tournament Championship Cap Award',
                'tagline' => 'Unique custom acrylic award for cap winners',
                'description' => 'A novel acrylic award designed in the shape of tournament caps (Orange/Purple caps). Features high-definition gloss printing and robust base.',
                'base_price' => 499
            ],
            'commen.jpg' => [
                'name' => 'Tournament Commendation Trophy',
                'tagline' => 'Appreciation award for participants and officials',
                'description' => 'A versatile and elegant commendation award to appreciate participants, players, and coordinators. High-quality print on custom-shaped acrylic.',
                'base_price' => 499
            ],
            'cric awards.jpg' => [
                'name' => 'Cricket Tournament Excellence Award',
                'tagline' => 'Multi-purpose cricket tournament trophy',
                'description' => 'Celebrate tournament highlights and outstanding achievements with this multipurpose acrylic cricket trophy. Custom-engraved text on wooden base.',
                'base_price' => 499
            ],
            'cric trophy.jpg' => [
                'name' => 'Manas Creations Cricket Trophy - Classic',
                'tagline' => 'Elegant premier cricket trophy',
                'description' => 'Our flagship classic cricket trophy. Perfect for schools, colleges, and corporate tournaments. UV printed on heavy-gauge glossy acrylic.',
                'base_price' => 499
            ],
            'ecl.jpg' => [
                'name' => 'ECL Tournament Championship Trophy',
                'tagline' => 'Grand large-sized tournament winner trophy',
                'description' => 'A massive, premium championship trophy designed specifically for league winners. Breathtaking gold-themed design with custom logo placement.',
                'base_price' => 499
            ],
            'motm.jpg' => [
                'name' => 'Man of the Match Trophy',
                'tagline' => 'Highly prestigious match-winner award',
                'description' => 'Reward the hero of the game with this premium \'Man of the Match\' acrylic trophy. Features stunning background graphics and professional gloss finish.',
                'base_price' => 499
            ],
            'mpl.jpg' => [
                'name' => 'MPL Tournament Winner Trophy',
                'tagline' => 'Magnificent premier league championship trophy',
                'description' => 'An exquisite league champion trophy with bold MPL styling. High durability, stunning gloss finish, and heavy polished wooden base.',
                'base_price' => 499
            ],
            'potm.jpg' => [
                'name' => 'Player of the Match Trophy',
                'tagline' => 'Modern acrylic award with dynamic cricket graphics',
                'description' => 'A gorgeous and modern Player of the Match trophy. Featuring stellar cricketer silhouettes and high contrast, vibrant colors.',
                'base_price' => 499
            ],
            'runnerup.jpg' => [
                'name' => 'Tournament Runner-Up Trophy',
                'tagline' => 'Elegant silver-themed acrylic runner-up award',
                'description' => 'Appreciate the finalists with this premium runner-up trophy. High-definition silver graphics on premium clear-cut gloss acrylic.',
                'base_price' => 499
            ],
            'tshirt motm.jpg' => [
                'name' => 'Custom T-Shirt Man of the Match Trophy',
                'tagline' => 'Unique jersey-styled player of the match award',
                'description' => 'An incredibly creative trophy styled as a cricket jersey. Custom printable with sponsor logos and player details on high-density acrylic.',
                'base_price' => 499
            ],
            'umpire.jpg' => [
                'name' => 'Tournament Fair Play & Umpire Trophy',
                'tagline' => 'Special appreciation trophy for match officials',
                'description' => 'Recognize outstanding sportsmanship, fair play, and the contribution of match umpires with this specially themed, premium acrylic trophy.',
                'base_price' => 499
            ],

            // Mandir
            'mandir 1.jpg' => [
                'name' => 'Divine Blessings Acrylic Mandir - Classic',
                'tagline' => 'Elegant designer home temple with LED provisions',
                'description' => 'Bring peace and divinity to your home with our beautifully designed classic acrylic mandir. Crafted with premium high-gloss acrylic sheets and intricate laser-cut jaali patterns. Sturdy, easy to clean, and features ample space for idols.',
                'base_price' => 1999
            ],
            'mandir 2.jpg' => [
                'name' => 'Premium Laser-Cut Acrylic Mandir',
                'tagline' => 'Exquisite design featuring intricate traditional patterns',
                'description' => 'A masterpiece of home decor, this premium mandir is laser-crafted with traditional temple patterns. Features robust acrylic columns and stunning glossy back panels that elevate your worship space.',
                'base_price' => 1999
            ],
            'mandir 3.jpg' => [
                'name' => 'Serene Puja Room Designer Mandir',
                'tagline' => 'Compact and elegant structure for modern apartments',
                'description' => 'Perfect for modern apartments, this compact designer mandir combines minimal space requirements with absolute elegance. Features beautiful carvings and high-durability glossy acrylic sheets.',
                'base_price' => 1999
            ],
            'mandir 4.jpg' => [
                'name' => 'Royal Golden-Accented Acrylic Mandir',
                'tagline' => 'Luxury temple with glossy finish and glowing accents',
                'description' => 'An opulent home mandir embellished with gorgeous royal golden-finished acrylic overlays. Exudes absolute luxury and forms a breathtaking center of worship in any household.',
                'base_price' => 1999
            ],
            'mandir 5.png' => [
                'name' => 'Majestic LED-lit Divine Mandir',
                'tagline' => 'Grand, wall-mountable acrylic temple with dynamic lighting space',
                'description' => 'Our grandest design yet. This wall-mountable acrylic temple comes with back-lighting setup space to create a celestial aura. Double-layered gloss acrylic provides incredible depth and shine.',
                'base_price' => 1999
            ],

            // Name Plate
            'name plate santosh bharti.png' => [
                'name' => 'Santosh Bharti Designer Name Plate',
                'tagline' => 'Premium customized acrylic house nameplate',
                'description' => 'Welcome your guests with this stunning designer nameplate. Crafted with double-layered high-density acrylic, embossed letters, and elegant weather-proof glossy finishes.',
                'base_price' => 999
            ],
            'name plate rs.png' => [
                'name' => 'Royal Crest Personalized Name Plate',
                'tagline' => 'Classic design with customized golden letters',
                'description' => 'A royal house nameplate featuring glossy gold acrylic letters overlaying a premium dark background. Absolute class and weather-resistant durability.',
                'base_price' => 999
            ],
            'name plate sm 2.png' => [
                'name' => 'Modern Minimalist Name Plate - Style II',
                'tagline' => 'Vibrant and weather-resistant door nameplate',
                'description' => 'Sleek, modern, and high contrast. This designer nameplate features beautiful modern fonts and clean lines, perfect for contemporary homes and apartments.',
                'base_price' => 999
            ]
        ];

        // Process categories folders
        foreach ($catMap as $categoryName => $categoryId) {
            $folderName = $categoryName;
            $catDir = $sourcePath . '/' . $folderName;

            if ($categoryName === 'Corporate Gift Hampers') {
                continue; // Processed separately below
            }

            if (!File::exists($catDir)) {
                $this->command->warn("Category directory does not exist: {$catDir}");
                continue;
            }

            $files = File::files($catDir);
            $this->command->info("Processing Category '{$categoryName}' - Found " . count($files) . " image files.");

            foreach ($files as $file) {
                $filename = $file->getFilename();
                
                // Copy image to public products folder
                $targetFile = $destPath . '/' . $filename;
                File::copy($file->getRealPath(), $targetFile);

                // Prepare metadata dictionary lookup (case-insensitive)
                $key = strtolower($filename);
                $info = $metadata[$key] ?? null;

                if ($info) {
                    $prodName = $info['name'];
                    $tagline = $info['tagline'];
                    $description = $info['description'];
                    $basePrice = $info['base_price'];
                } else {
                    // Fallback to pretty file name
                    $cleanName = pathinfo($filename, PATHINFO_FILENAME);
                    $cleanName = str_replace(['_', '-'], ' ', $cleanName);
                    $cleanName = ucwords(strtolower($cleanName));

                    $prodName = $cleanName;
                    $tagline = "Premium customized {$categoryName} design";
                    $description = "Exquisite handcrafted personalized {$categoryName} item. Created on premium heavy-gloss acrylic with vibrant color printing and a flawless glass-look polish. Perfect for gifts and home decor.";
                    
                    // Assign category base price fallback
                    switch ($categoryName) {
                        case 'Caricatures':
                            $basePrice = 399;
                            break;
                        case 'Cricket Trophy':
                            $basePrice = 499;
                            break;
                        case 'Mandir':
                            $basePrice = 1999;
                            break;
                        case 'Name Plate':
                            $basePrice = 999;
                            break;
                        default:
                            $basePrice = 299;
                    }
                }

                // Create the product
                $product = Product::create([
                    'name' => $prodName,
                    'category_id' => $categoryId,
                    'tagline' => $tagline,
                    'description' => $description,
                    'images' => ['products/' . $filename], // Store as products/filename relative to public storage disk
                    'rating' => mt_rand(45, 50) / 10,
                    'reviews_count' => rand(12, 75),
                    'is_active' => true,
                ]);

                // Create exactly one Standard variant
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => 'Standard',
                    'mrp' => $basePrice * 1.5,
                    'discount' => 33, // ~33% off MRP
                    'stock' => rand(15, 50),
                ]);
            }
        }

        // Manually seed Corporate Gift Hamper product
        if (isset($catMap['Corporate Gift Hampers'])) {
            $hampId = $catMap['Corporate Gift Hampers'];
            
            // Check if file exists in base path products directory or target public products directory
            $hamperFilename = 'corporate_gift_hamper.png';
            $hamperSource = base_path('public/storage/products/' . $hamperFilename);
            if (!File::exists($hamperSource)) {
                $hamperSource = storage_path('app/public/products/' . $hamperFilename);
            }
            
            // Ensure the destination has it
            $targetHamperFile = $destPath . '/' . $hamperFilename;
            if (File::exists($hamperSource) && !File::exists($targetHamperFile)) {
                File::copy($hamperSource, $targetHamperFile);
            }

            $product = Product::create([
                'name' => 'Signature Corporate Celebration Gift Hamper',
                'category_id' => $hampId,
                'tagline' => 'Premium luxury customized corporate gift hamper',
                'description' => 'A luxurious collection of curated premium items, perfect for corporate clients, festival gifting, and employee appreciation. Beautifully packaged in an elegant gold-embossed box with custom branding options.',
                'images' => ['products/' . $hamperFilename],
                'rating' => 4.9,
                'reviews_count' => 48,
                'is_active' => true,
            ]);

            ProductVariant::create([
                'product_id' => $product->id,
                'size' => 'Standard',
                'mrp' => 2499,
                'discount' => 20, // 20% off
                'stock' => 35,
            ]);
        }
    }
}

