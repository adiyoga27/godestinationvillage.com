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
        return view('customer/blog', $data);
    }
    public function detailpost($slug)
    {
        $id = Blog::where('slug', $slug)->first()->id;

        $data['blog'] = Blog::where('isPublished', '1')->find($id);
        if (!$data['blog']) {
            return abort(404);
        }
        $data['recent'] = Blog::where('isPublished', '1')->latest('id')->limit(5)->get();
        $data['comments'] = PostComment::with('users')->whereHas('blog', function($q) use($slug){
             $q->where('slug', $slug);
        })->where('parent_id', 0)->orderBy('id', 'desc')->get();
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
            $data['packages'] = Package::with(['category', 'user', 'village'])
                                            ->where('village_id', $result->id)
                                            ->where('packages.is_active', '1')
                                            ->paginate(8);
                                            
    // dd($data);
    
            $data['recent'] = Package::select('packages.id','packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'default_img', 'packages.slug')
                    ->join('users', 'users.id', 'user_id')
                    ->join('village_details', 'users.id', 'village_details.user_id')
                    ->join('categories', 'categories.id', 'category_id')
                    ->where('users.is_active', '1')
                    ->where('packages.is_active', '1')
                    ->orderBy('packages.id', 'desc')
                    ->limit(5)->get();
            return view('customer/detailvillage', $data);
        } catch (\Throwable $th) {
            return abort(404);
        }
      
    }
    public function tourpackages()
    {
        $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img')->join('users', 'users.id', 'user_id')->join('village_details', 'users.id', 'village_details.user_id')->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->paginate(10);
        // dd($data);
        return view('customer/tourpackages', $data);
    }
    public function homeStay()
    {
        $data['packages'] = HomeStayServices::active();
        // dd($data);
        return view('customer/homestay', $data);
    }
    public function eventsGodevi()
    {
        // $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img', 'paywish')->join('users', 'users.id', 'user_id')->join('village_details', 'users.id', 'village_details.user_id')->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->where('packages.category_id', '5')->paginate(10);
        $data['packages'] = EventService::active();
        return view('customer/events', $data);
    }
    public function categorypackage(Request $request, $id)
    {
        $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img', 'packages.slug')
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
        $id = Package::where('slug', $slug)->first()->id;

        $data['instagram'] = InstagramServices::randomPost();
        $data['images'] = Storage::files('packages/' . $id);
            $data['packages'] = Package::with(['village', 'category','translate'])->where('id', $id)
            ->first();
        if (!$data['packages']) {
            return abort(404);
        }
        $data['recent'] = Package::select('packages.id', 'packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'default_img','packages.slug')
                                    ->join('users', 'users.id', 'user_id')
                                    ->join('village_details', 'users.id', 'village_details.user_id')
                                    ->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->orderBy('packages.id', 'desc')->limit(5)->get();
        // $data['recent'] = Package::orderBy('desc')->limit(5)->get();
        // var_dump($data['packages']);
        return view('customer/detailtour', $data);
    }
    public function detailEvent($slug)
    {

        $id = Event::where('slug', $slug)->first()->id;
        $data['instagram'] = InstagramServices::randomPost();
        $data['images'] = Storage::files('events/' . $id);
            $data['packages'] = Event::with(['category','translate'])->where('id', $id)
            ->first();
        if (!$data['packages']) {
            return abort(404);
        }
        $data['recent'] = EventService::recent();
        // $data['recent'] = Package::select('packages.id', 'packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'default_img')
        //                             ->join('users', 'users.id', 'user_id')
        //                             ->join('village_details', 'users.id', 'village_details.user_id')
        //                             ->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->orderBy('packages.id', 'desc')->limit(5)->get();
        return view('customer/detailevent', $data);
    }
    public function detailHomestay($slug)
    {
        $id = Homestay::where('slug', $slug)->first()->id;

        $data['instagram'] = InstagramServices::randomPost();

        $data['images'] = Storage::files('homestay/' . $id);
            $data['packages'] = Homestay::with(['category','translate'])->where('id', $id)
            ->first();
        if (!$data['packages']) {
            return abort(404);
        }
$data['recent'] = HomeStayServices::recent();
        // $data['recent'] = Package::select('packages.id', 'packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'default_img')
        //                             ->join('users', 'users.id', 'user_id')
        //                             ->join('village_details', 'users.id', 'village_details.user_id')
        //                             ->join('categories', 'categories.id', 'category_id')->where('users.is_active', '1')->where('packages.is_active', '1')->orderBy('packages.id', 'desc')->limit(5)->get();
        return view('customer/detailhomestay', $data);
    }
    public function faq()
    {
        return view('customer/faq');
    }
    public function services()
    {
        return view('customer/services');
    }
    public function term()
    {

        return view('customer/terms');
    }
    
    public function deleteAccount(){
        return view('customer/delete-account');
    }
    public function ourteam()
    {
        $ours = OurTeam::all();
        return view('customer/ourteam', compact('ours'));
    }
    public function founding()
    {
        $foundings = Founding::all();
        return view('customer/founding', compact('foundings'));
    } 
    public function portofolio()
    {
        $portofolios = Portofolio::orderby('dates', 'DESC')->get();
        return view('customer/portofolio', compact('portofolios'));
    }
    public function boardExpert()
    {
        $boards = BoardExpert::all();
        return view('customer/boardexpert', compact('boards'));
    }
    public function ourpartner()
    {
        return view('customer/ourpartner');
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
        return view('customer/contact');
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
        return view('customer/bookformHomeStay', $data);
    }
    public function account()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $data['user'] = User::where('id', $userId)
                ->first();
        }
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
        return view('customer/login');
    }
    public function register()
    {
        return view('auth/register');
    }
    public function companyprofile()
    {
        return view('customer/companyprofile');
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
