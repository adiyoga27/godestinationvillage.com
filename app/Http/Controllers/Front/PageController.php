<?php
namespace App\Http\Controllers\Front;

use App\Helpers\BotHelper;
use App\Models\Blog;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Package;
use App\Models\Category;
use App\Models\BankAccount;
use App\Helpers\CustomImage;
use Illuminate\Http\Request;
use App\Models\BoardExpert;
use App\Models\Certification;
use App\Models\Event;
use App\Models\Founding;
use App\Models\Homestay;
use App\Models\OrderEvent;
use App\Models\OrderHomestay;
use App\Models\OurTeam;
use App\Models\Portofolio;
use App\PackageTranslations;
use App\Models\Tag;
use App\Models\PostComment;
use App\Models\VillageDetail;
use App\Services\EventService;
use App\Services\HomeStayServices;
use App\Services\InstagramServices;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Support\Seo;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class PageController extends Controller
{

    public function index()
    {
       
        $data['village'] = User::where('role_id', '2')->where('is_active', '1')->limit(8)->get();
        $data['packages'] = Package::orderBy('desc')->limit(8)->get();
        $data['recent_blog'] = Blog::with('user')->where('isPublished', '1')->latest('id')->limit(5)->get();
        $data['category'] = Category::All();
        $data['users'] = Storage::files('reviews');
        $data['reviews'] = Review::with('users')->where('is_active', 1)->get();
        $data['tag'] = Tag::all();
        $data['seo'] = Seo::make()
            ->title('Authentic Village Experiences in Bali')
            ->description('GODEVI (Go Destination Village) connects travelers with authentic Balinese village experiences — village tours, homestays, events and socially responsible tourism packages in Bali, Indonesia.')
            ->keywords(['village tourism bali', 'homestay bali', 'desa wisata bali', 'godevi', 'bali village tour'])
            ->image('assets/customer/img/logo.png')
            ->canonical('/')
            ->organizationSchema()
            ->websiteSchema()
            ->webPageSchema('GODEVI - Authentic Village Experiences in Bali')
            ->toArray();
        return view('customer.home', $data);
    }
    public function homebaru()
    {
        $data['village'] = User::where('role_id', '2')->where('is_active', '1')->limit(8)->get();
        $data['packages'] = Package::orderBy('desc')->limit(8)->get();
        $data['recent_blog'] = Blog::where('isPublished', '1')->latest('id')->limit(5)->get();
        $data['category'] = Category::All();
        return view('customer/homebaru', $data);
    }
    public function blog()
    {
        $data['blog'] = Blog::where('isPublished', '1')->latest('id')->paginate(5);
        $data['recent'] = Blog::where('isPublished', '1')->latest('id')->limit(4)->get();
        $data['seo'] = Seo::make()
            ->title('News & Insights')
            ->description('Read the latest news, stories and insights about sustainable village tourism in Bali from GODEVI — community empowerment, homestay experiences and authentic travel.')
            ->keywords(['godevi news', 'village tourism news bali', 'desa wisata', 'sustainable tourism'])
            ->canonical('/news')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'News' => '/news'])
            ->toArray();
        return view('customer/blog', $data);
    }
    public function detailpost($slug)
    {
        $data['blog'] = Blog::where('isPublished', '1')->where('slug', $slug)->first();
        if (!$data['blog']) {
            return abort(404);
        }
        $id = $data['blog']->id;
        $data['recent'] = Blog::where('isPublished', '1')->latest('id')->limit(5)->get();
        $data['comments'] = PostComment::with('users')->whereHas('blog', function($q) use($slug){
             $q->where('slug', $slug);
        })->where('parent_id', 0)->orderBy('id', 'desc')->get();
        $data['seo'] = Seo::make()
            ->title($data['blog']->post_title)
            ->description(Str::words(strip_tags($data['blog']->post_content), 30, '...'))
            ->image('storage/blogs/'.$data['blog']->post_thumbnail)
            ->keywords(collect(explode(',', $data['blog']->post_tags))->filter()->values()->all())
            ->canonical('/news/'.$slug)
            ->type('article')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'News' => '/news', $data['blog']->post_title => '/news/'.$slug])
            ->articleSchema([
                'headline' => $data['blog']->post_title,
                'datePublished' => $data['blog']->created_at?->toIso8601String(),
                'dateModified' => $data['blog']->updated_at?->toIso8601String(),
                'author' => ['@type' => 'Person', 'name' => $data['blog']->user?->name ?? 'GODEVI'],
            ])
            ->toArray();
        return view('customer/detail-blog', $data);
    }
    public function blog_mobile()
    {
        $data['blog'] = Blog::where('isPublished', '1')->latest('id')->paginate(5);
        $data['recent'] = Blog::where('isPublished', '1')->latest('id')->limit(4)->get();
        return view('customer/blog-mobile', $data);
    }
    public function detailpost_mobile($id)
    {
        $data['blog'] = Blog::where('isPublished', '1')->find($id);
        if (!$data['blog']) {
            return abort(404);
        }
        $data['recent'] = Blog::where('isPublished', '1')->latest('created_at')->limit(5)->get();
        return view('customer/detail-blog-mobile', $data);
    }
    public function village()
    {
        $data['village'] = User::with(['village_detail'])->where('role_id', '2')->where('is_active', '1')->paginate(30);
        $data['seo'] = Seo::make()
            ->title('Explore Villages in Bali')
            ->description('Discover authentic Balinese villages with GODEVI. Explore village tourism destinations, community homestays and immersive local experiences across Bali.')
            ->keywords(['desa wisata bali', 'village tourism bali', 'explore villages', 'balinese village'])
            ->canonical('/village')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Explore Village' => '/village'])
            ->toArray();
        return view('customer/village', $data);
    }
    public function certification($id)
    {
        $data['certificate'] = Certification::where('slug', $id)->first();
        if($data['certificate'] != null){
            return view('customer/certificate', $data);
        }
        return view('errors/404');
    }
    public function detailVillage($slug)
    {
        try {
            $result = VillageDetail::where('slug', $slug)->first();
            // dd($result);
            $data['village'] = User::where('users.id', $result->user_id)
                ->where('is_active', '1')
                ->where('role_id', '2')
                ->first();
    
            if (!$data['village']) {
                return abort(404);
            }
            // dd($result->id);
            $data['packages'] = Package::with(['category', 'user', 'village', 'translate'])
                                            ->where('village_id', $result->id)
                                            ->where('packages.is_active', '1')
                                            ->paginate(8);
                                            
    // dd($data);
    
            $data['recent'] = Package::with('translate')->select('packages.id','packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'default_img', 'packages.slug')
                    ->join('users', 'users.id', 'user_id')
                    ->join('village_details', 'users.id', 'village_details.user_id')
                    ->join('categories', 'categories.id', 'category_id')
                    ->where('users.is_active', '1')
                    ->where('packages.is_active', '1')
                    ->orderBy('packages.id', 'desc')
                    ->limit(5)->get();
            $data['seo'] = Seo::make()
                ->title($result->village_name.' Village Tourism')
                ->description(Str::limit(strip_tags($result->desc ?? ''), 158))
                ->image('storage/village/'.$result->image ?? null)
                ->keywords([$result->village_name, 'desa wisata', 'village tourism bali'])
                ->canonical('/village/'.$slug)
                ->type('place')
                ->organizationSchema()
                ->websiteSchema()
                ->breadcrumbSchema(['Home' => '/', 'Explore Village' => '/village', $result->village_name => '/village/'.$slug])
                ->schema([
                    '@context' => 'https://schema.org',
                    '@type' => 'TouristDestination',
                    'name' => $result->village_name,
                    'description' => $result->desc ?? null,
                    'url' => '/village/'.$slug,
                    'address' => ['@type' => 'PostalAddress', 'addressRegion' => 'Bali', 'addressCountry' => 'ID'],
                ])
                ->toArray();
            return view('customer/detailvillage', $data);
        } catch (\Throwable $th) {
            return abort(404);
        }
      
    }
    public function tourpackages()
    {
        $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img')->with('translate')->join('users', 'users.id', 'user_id')->join('village_details', 'users.id', 'village_details.user_id')->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->paginate(10);
        $data['seo'] = Seo::make()
            ->title('Tour Packages & Experiences')
            ->description('Browse affordable bali village adventure packages with GODEVI — immersive tours, cultural experiences and socially responsible travel in Bali villages.')
            ->keywords(['bali tour packages', 'village tour bali', 'desa wisata bali paket', 'cultural experiences'])
            ->canonical('/tour-packages')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Tour Packages' => '/tour-packages'])
            ->toArray();
        return view('customer/tourpackages', $data);
    }
    public function homeStay()
    {
        $data['packages'] = HomeStayServices::active();
        $data['seo'] = Seo::make()
            ->title('Bali Homestay & Village Stay')
            ->description('Stay overnight in authentic Balinese homestays with GODEVI. Immerse yourself in village life, local traditions and warm Balinese hospitality.')
            ->keywords(['bali homestay', 'village homestay bali', 'desa wisata menginap', 'homestay godevi'])
            ->canonical('/homestay')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Homestay' => '/homestay'])
            ->toArray();
        return view('customer/homestay', $data);
    }
    public function eventsGodevi()
    {
        // $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img', 'paywish')->join('users', 'users.id', 'user_id')->join('village_details', 'users.id', 'village_details.user_id')->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->where('packages.category_id', '5')->paginate(10);
        $data['packages'] = EventService::active();
        $data['seo'] = Seo::make()
            ->title('Village Events & Festivals')
            ->description('Discover authentic village events and cultural festivals in Bali with GODEVI. Join local ceremonies, workshops and community activities.')
            ->keywords(['bali events', 'village festival bali', 'cultural events bali', 'godevi events'])
            ->canonical('/events')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Events' => '/events'])
            ->toArray();
        return view('customer/events', $data);
    }
    public function categorypackage(Request $request, $id)
    {
        $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img', 'packages.slug')
            ->with('translate')
            ->leftjoin('users', 'users.id', '=', 'packages.user_id')
            ->leftjoin('village_details', 'users.id','=', 'village_details.user_id')
            ->join('categories', 'categories.id', 'category_id')
            // ->where('users.is_active', '1')
            ->where('packages.is_active', '1')
            ->where('packages.tag_id', $id)
            ->orderBy('packages.id', 'DESC')
            ->paginate(10);
        // dd($data);
        return view('customer/tourpackages', $data);
    }
    public function detailtour($slug)
    {
        $package = Package::where('slug', $slug)->first();
        if (!$package) {
            return abort(404);
        }
        $id = $package->id;

        $data['instagram'] = InstagramServices::randomPost();
        $data['images'] = Storage::files('packages/' . $id);
            $data['packages'] = Package::with(['village', 'category','translate'])->where('id', $id)
            ->first();
        if (!$data['packages']) {
            return abort(404);
        }
        $data['recent'] = Package::with('translate')->select('packages.id', 'packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'default_img','packages.slug')
                                    ->join('users', 'users.id', 'user_id')
                                    ->join('village_details', 'users.id', 'village_details.user_id')
                                    ->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->orderBy('packages.id', 'desc')->limit(5)->get();
        $data['seo'] = Seo::make()
            ->title($data['packages']->name)
            ->description(Str::limit(strip_tags($data['packages']->desc ?? ''), 158))
            ->image('storage/packages/'.$data['packages']->default_img)
            ->keywords([$data['packages']->name, 'bali village tour', 'tour package bali'])
            ->canonical('/tour-packages/'.$slug)
            ->type('product')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Tour Packages' => '/tour-packages', $data['packages']->name => '/tour-packages/'.$slug])
            ->schema([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $data['packages']->name,
                'description' => $data['packages']->desc ?? null,
                'image' => url('storage/packages/'.$data['packages']->default_img),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $data['packages']->price ?? 0,
                    'priceCurrency' => 'IDR',
                    'availability' => 'https://schema.org/InStock',
                ],
            ])
            ->toArray();
        // $data['recent'] = Package::orderBy('desc')->limit(5)->get();
        // var_dump($data['packages']);
        return view('customer/detailtour', $data);
    }
    public function detailEvent($slug)
    {

        $event = Event::where('slug', $slug)->first();
        if (!$event) {
            return abort(404);
        }
        $id = $event->id;
        $data['instagram'] = InstagramServices::randomPost();
        $data['images'] = Storage::files('events/' . $id);
            $data['packages'] = Event::with(['category','translate'])->where('id', $id)
            ->first();
        if (!$data['packages']) {
            return abort(404);
        }
        $data['recent'] = EventService::recent();
        $data['seo'] = Seo::make()
            ->title($data['packages']->name)
            ->description(Str::limit(strip_tags($data['packages']->description ?? ''), 158))
            ->image('storage/events/'.$data['packages']->default_img)
            ->keywords([$data['packages']->name, 'bali village event', 'cultural event'])
            ->canonical('/events/'.$slug)
            ->type('event')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Events' => '/events', $data['packages']->name => '/events/'.$slug])
            ->schema([
                '@context' => 'https://schema.org',
                '@type' => 'Event',
                'name' => $data['packages']->name,
                'description' => $data['packages']->description ?? null,
                'image' => url('storage/events/'.$data['packages']->default_img),
                'location' => $data['packages']->location ? ['@type' => 'Place', 'name' => $data['packages']->location] : null,
                'startDate' => $data['packages']->date_event ? \Illuminate\Support\Carbon::parse($data['packages']->date_event)->toIso8601String() : null,
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $data['packages']->price ?? 0,
                    'priceCurrency' => 'IDR',
                    'url' => '/events/'.$slug,
                ],
            ])
            ->toArray();
        return view('customer/detailevent', $data);
    }
    public function detailHomestay($id)
    {
        $data['instagram'] = InstagramServices::randomPost();

        $data['images'] = Storage::files('homestay/' . $id);
            $data['packages'] = Homestay::with(['category','translate'])->where('id', $id)
            ->first();
        if (!$data['packages']) {
            return abort(404);
        }
$data['recent'] = HomeStayServices::recent();
        $data['seo'] = Seo::make()
            ->title($data['packages']->name)
            ->description(Str::limit(strip_tags($data['packages']->description ?? ''), 158))
            ->image('storage/homestay/'.$data['packages']->default_img)
            ->keywords([$data['packages']->name, 'bali homestay', 'village stay'])
            ->canonical('/homestay/'.$data['packages']->id)
            ->type('product')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Homestay' => '/homestay', $data['packages']->name => '/homestay/'.$data['packages']->id])
            ->schema([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $data['packages']->name,
                'description' => $data['packages']->description ?? null,
                'image' => url('storage/homestay/'.$data['packages']->default_img),
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $data['packages']->price ?? 0,
                    'priceCurrency' => 'IDR',
                    'availability' => 'https://schema.org/InStock',
                ],
            ])
            ->toArray();
        return view('customer/detailhomestay', $data);
    }
    public function faq()
    {
        $data['seo'] = Seo::make()
            ->title('Frequently Asked Questions')
            ->description('Answers to common questions about GODEVI village tourism, homestays, booking, payments and travel experiences in Bali.')
            ->keywords(['godevi faq', 'village tourism faq', 'homestay booking faq'])
            ->canonical('/faq')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'FAQ' => '/faq'])
            ->toArray();

        return view('customer/faq', $data);
    }
    public function services()
    {
        $data['seo'] = Seo::make()
            ->title('Our Services')
            ->description('GODEVI services — tourism planning and strategy, village revitalization, project management, human resources development, destination branding and research.')
            ->keywords(['godevi services', 'tourism planning bali', 'destination branding', 'research tourism'])
            ->canonical('/services')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Our Services' => '/services'])
            ->toArray();

        return view('customer/services', $data);
    }
    public function term()
    {
        $data['seo'] = Seo::make()
            ->title('Terms & Conditions')
            ->description('Terms and conditions for booking tours, homestays and events with GODEVI (Go Destination Village).')
            ->canonical('/term')
            ->noindex()
            ->toArray();

        return view('customer/terms', $data);
    }
    
    public function deleteAccount(){
        $data['seo'] = Seo::make()->title('Delete Account')->noindex()->toArray();

        return view('customer/delete-account', $data);
    }
    public function ourteam()
    {
        $ours = OurTeam::all();
        $data = compact('ours');
        $data['seo'] = Seo::make()
            ->title('Our Team')
            ->description('Meet the passionate team behind GODEVI who are dedicated to uplifting local communities through socially responsible village tourism in Bali.')
            ->canonical('/our-team')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Our Team' => '/our-team'])
            ->toArray();

        return view('customer/ourteam', $data);
    }
    public function founding()
    {
        $foundings = Founding::all();
        $data = compact('foundings');
        $data['seo'] = Seo::make()
            ->title('The Founding')
            ->description('The story behind the founding of GODEVI — Go Destination Village, a socially pro-active business dedicated to uplifting village communities in Bali.')
            ->canonical('/v-founding')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'The Founding' => '/v-founding'])
            ->toArray();

        return view('customer/founding', $data);
    } 
    public function portofolio()
    {
        $portofolios = Portofolio::orderby('dates', 'DESC')->get();
        $data = compact('portofolios');
        $data['seo'] = Seo::make()
            ->title('Our Portfolio')
            ->description('Explore GODEVI portfolio — village tourism projects, community empowerment programs and sustainable tourism initiatives across Bali.')
            ->canonical('/v-portofolio')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Portfolio' => '/v-portofolio'])
            ->toArray();

        return view('customer/portofolio', $data);
    }
    public function boardExpert()
    {
        $boards = BoardExpert::all();
        $data = compact('boards');
        $data['seo'] = Seo::make()
            ->title('Board of Experts')
            ->description('Meet the board of experts guiding GODEVI in sustainable tourism, community development and destination management.')
            ->canonical('/v-board')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Board of Experts' => '/v-board'])
            ->toArray();

        return view('customer/boardexpert', $data);
    }
    public function ourpartner()
    {
        $data['seo'] = Seo::make()
            ->title('Our Partners')
            ->description('The partners and collaborators supporting GODEVI in building sustainable village tourism communities across Bali.')
            ->canonical('/our-partner')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Our Partners' => '/our-partner'])
            ->toArray();

        return view('customer/ourpartner', $data);
    }
    public function reservation(Request $request)
    {
        $end_date=date("Y-m-d H:i:s",strtotime("-2 month",strtotime(date("Y-m-01",strtotime("now") ) )));
        $data['order'] = Order::with('package')->where('payment_status', 'pending')
            ->whereNotNull('uuid')
            ->where('customer_email', $request->email)
            ->where('created_at', '>=', $end_date)
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['isiemail'] = $request->email;
        return view('customer/reservation/reservation', $data);
    }
    public function contact()
    {
        $data['seo'] = Seo::make()
            ->title('Contact Us')
            ->description('Get in touch with GODEVI — Go Destination Village. Reach us by phone, email or visit us in Denpasar, Bali for village tourism and homestay inquiries.')
            ->keywords(['contact godevi', 'godevi contact', 'village tourism bali contact'])
            ->canonical('/contact')
            ->organizationSchema()
            ->websiteSchema()
            ->webPageSchema('Contact GODEVI - Go Destination Village')
            ->toArray();

        return view('customer/contact', $data);
    }
    public function payment($id)
    {
        $order = Order::where('uuid',$id)->first()->toArray();
        $price = ($order['package_price']-$order['package_discount']);
        $request = [
            'transaction_details' => [
                'order_id' => $order['code'],
                'gross_amount' => $order['total_payment'],
            ],
            'item_details' => [
                [
                    'id' => $order['package_id'],
                    'price' => $price,
                    'quantity' => $order['pax'],
                    'name' => Str::limit($order['package_name'],30),
                ],
            ],
            'customer_details' => [
                'first_name' => $order['customer_name'],
                'email' => $order['customer_email'],
                'phone' => $order['customer_phone'],
            ]
        ];
            // Jika snap token masih NULL, buat token snap dan simpan ke database
            $snapToken = $order['snap_token'];
            if($snapToken == null){
                $midtrans = new CreateSnapTokenService($order);
                $snapToken = $midtrans->getSnapToken($request);
                Order::where('uuid', $id)->update([
                    'snap_token' => $snapToken
                ]);
            }
            $data['snapToken'] = $snapToken;
            $data['order'] =  $order;
            $data['redirectURISuccess'] =  url("reservation/paid/".$order['customer_email']);
            $data['redirectURIError'] = url("reservation/".$order['customer_email']);
        // dd($data);
        return view('customer/payment/midtrans', $data);
    }
    public function paymentEvent($id)
    {
        $order = OrderEvent::where('uuid',$id)->first()->toArray();
        $request = [
            'transaction_details' => [
                'order_id' => $order['code'],
                'gross_amount' => $order['total_payment'],
            ],
            'item_details' => [
                [
                    'id' => $order['event_id'],
                    'price' => $order['event_price'],
                    'quantity' => $order['pax'],
                    'name' => $order['event_name'],
                ],
            ],
            'customer_details' => [
                'first_name' => $order['customer_name'],
                'email' => $order['customer_email'],
                'phone' => $order['customer_phone'],
            ]
        ];
            // Jika snap token masih NULL, buat token snap dan simpan ke database
            $snapToken = $order['snap_token'];
            if($snapToken == null){
                $midtrans = new CreateSnapTokenService($order);
                $snapToken = $midtrans->getSnapToken($request);
                OrderEvent::where('uuid', $id)->update([
                    'snap_token' => $snapToken
                ]);
            }
            $data['snapToken'] = $snapToken;
            $data['order'] =  $order;
            $data['redirectURISuccess'] =  url("reservation-events/paid/".$order['customer_email']);
            $data['redirectURIError'] = url("reservation-events/".$order['customer_email']);
        // dd($data);
        return view('customer/payment/midtrans', $data);
    }
    public function paymentHomestay($id)
    {
        $order = OrderHomestay::where('uuid',$id)->first()->toArray();
        $request = [
            'transaction_details' => [
                'order_id' => $order['code'],
                'gross_amount' => $order['total_payment'],
            ],
            'item_details' => [
                [
                    'id' => $order['homestay_id'],
                    'price' => $order['homestay_price'],
                    'quantity' => $order['pax'],
                    'name' => $order['homestay_name'],
                ],
            ],
            'customer_details' => [
                'first_name' => $order['customer_name'],
                'email' => $order['customer_email'],
                'phone' => $order['customer_phone'],
            ]
        ];
            // Jika snap token masih NULL, buat token snap dan simpan ke database
            $snapToken = $order['snap_token'];
            if($snapToken == null){
                $midtrans = new CreateSnapTokenService($order);
                $snapToken = $midtrans->getSnapToken($request);
                OrderHomestay::where('uuid', $id)->update([
                    'snap_token' => $snapToken
                ]);
            }
            $data['snapToken'] = $snapToken;
            $data['order'] =  $order;
            $data['redirectURISuccess'] =  url("reservation-homestay/paid/".$order['customer_email']);
            $data['redirectURIError'] = url("reservation-homestay/".$order['customer_email']);
        // dd($data);
        return view('customer/payment/midtrans', $data);
    }
    public function detailPayment($id)
    {
        $data['order'] = Order::whereNotNull('payment_type')->with('bank_account')
            ->where('id', $id)
            ->first();
        $data['bank'] =  BankAccount::all();
        return view('customer/payment/detail', $data);
    }
    public function confirmPayment($id)
    {
        $data['order'] = Order::whereNotNull('payment_type')->with('bank_account')
            ->where('id', $id)
            ->first();
        $data['bank'] =  BankAccount::all();
        return view('customer/payment/confirmation', $data);
    }
    public function cancel($id)
    {
        $proses = Order::where('uuid',$id);
        $proses->payment_status = 'cancel';
        $proses->save();
        if ($proses) {
            return redirect('reservation/cancel/' . $proses->customer_email);
        }
    }
    public function cancelEvent($id)
    {
        $proses = OrderEvent::where('uuid',$id);
        $proses->payment_status = 'cancel';
        $proses->save();
        if ($proses) {
            return redirect('reservation-events/cancel/' . $proses->customer_email);
        }
    }
    public function cancelHomeStay($id)
    {

        $proses = OrderHomestay::where('uuid',$id);
        $proses->payment_status = 'cancel';
        $proses->save();
        if ($proses) {
            return redirect('reservation-homestay/cancel/' . $proses->customer_email);
        }
    }
    public function booking($id)
    {
        $data['packages'] = Package::with('detailVillage')->where('id', $id)
            ->first();
        if (Auth::check()) {
            $userId = Auth::id();
            $data['user'] = User::where('id', $userId)
                ->first();
        }
        $data['seo'] = Seo::make()->title('Book Your Experience')->noindex()->toArray();

        return view('customer/bookform', $data);
    }
    public function bookingEvents($id)
    {
        $data['packages'] = Event::where('id', $id)
            ->first();
        if (Auth::check()) {
            $userId = Auth::id();
            $data['user'] = User::where('id', $userId)
                ->first();
        }
        $data['seo'] = Seo::make()->title('Book Event')->noindex()->toArray();

        return view('customer/bookformEvents', $data);
    }
    public function bookingHomeStay($id)
    {
        $data['packages'] = Homestay::where('id', $id)
            ->first();
        if (Auth::check()) {
            $userId = Auth::id();
            $data['user'] = User::where('id', $userId)
                ->first();
        }
        $data['seo'] = Seo::make()->title('Book Homestay')->noindex()->toArray();

        return view('customer/bookformHomeStay', $data);
    }
    public function account()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $data['user'] = User::where('id', $userId)
                ->first();
        }
        $data['seo'] = Seo::make()->title('My Account')->noindex()->toArray();

        return view('customer/account', $data);
    }
    public function accountUpdate(Request $request)
    {
        try {
            if (!empty($request['uploadfile'])) {
                $upload = CustomImage::storeImage($request->file('uploadfile'), 'users');
                $payload['avatar'] = $upload['name'];
            }
            $payload['name'] = $request['customername'];
            $payload['email'] = $request['email'];
            $payload['phone'] = $request['phone'];
            $payload['country'] = $request['country'];
            $payload['address'] = $request['address'];
            // dd($payload);
            User::where('id', $request['customerid'])->update($payload);
        } catch (\Throwable $th) {
            BotHelper::errorBot('Update Profile', $th);
            return $th;
        }
        return redirect('account');
    }
    public function login()
    {
        $data['seo'] = Seo::make()->title('Login')->noindex()->toArray();

        return view('customer/login', $data);
    }
    public function register()
    {
        $data['seo'] = Seo::make()->title('Register')->noindex()->toArray();

        return view('auth/register', $data);
    }
    public function companyprofile()
    {
        $data['seo'] = Seo::make()
            ->title('Company Profile')
            ->description('Learn about GODEVI (PT Banua Wisata Lestari) — our vision, mission and commitment to socially responsible and sustainable village tourism in Bali.')
            ->canonical('/company-profile')
            ->organizationSchema()
            ->websiteSchema()
            ->breadcrumbSchema(['Home' => '/', 'Company Profile' => '/company-profile'])
            ->toArray();

        return view('customer/companyprofile', $data);
    }

    
    public function postComment(Request $request, $slug)
    {
        if(!Auth::check()){
            return redirect()->back()->with('error', 'Please login first');
        }
        $request->validate([
            'comment' => 'required'
        ]);

        $blog = Blog::where('slug', $slug)->firstOrFail();
        
        PostComment::create([
            'post_id' => $blog->id,
            'user_id' => Auth::user()->id,
            'parent_id' => 0,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'Comment posted successfully');
    }
}
