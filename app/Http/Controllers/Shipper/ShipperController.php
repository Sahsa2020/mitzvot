<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\House;
use App\Models\HouseIo;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Session;
use File;
use Config;
use Auth;
use DB;
use Mail;
class ShipperController extends Controller
{

    public function __construct()
    {
        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/shipper_menus.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/shipper_menus.json')));
            view()->share('shipperMenus', $menus);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        return view('shipper.dashboard');
    }

    public function orders(Request $request){
        $param = $request->only(['state']);
        $user = Auth::user();
        if(!$user->can('SHIP_MANAGE')) {
            Session::flash('flash_message', 'Permission required!');
            return view('shipper.orders.index');
        }
        $orders = [];
        $state = $param['state'];
        if($state == 'APPOINTED')
            $orders = Order::where('del_flg', config('constants.BOX_SHIP_APPOINTED'))->orderby('created_at', 'desc')->paginate(10);
        else if($state == 'DONE')
            $orders = Order::where('del_flg', config('constants.BOX_SHIP_DONE'))->orderby('created_at', 'desc')->paginate(10);
        else{
            $orders = Order::where('del_flg', '<>', config('constants.BOX_SHIP_CANCELLED'))->orderby('created_at', 'desc')->paginate(10);
            $state = "";
        }
        foreach($orders as $order){
            $houseio = HouseIo::where('order_id', $order->id)->first();
            if($houseio != null)
                $order->shipped_amount = -$houseio->amount;
        }
        return view('shipper.orders.index', compact('orders', 'state'));
    }

    public function editOrder($id, Request $request){
        $order = Order::findOrFail($id);
        $_houses = House::where('del_flg', config('constants.ITEM_IS_LIVE'))->get();
        $houseIo = HouseIo::where('order_id', $id)->first();
        $order->house = 0;
        $order->date = date('Y-m-d', strtotime($order->created_at));
        $houses = [];
        foreach($_houses as $house){
            $houses[$house->id] = $house->name.' -- '.$house->city.' -- '.$house->state.' -- '.$house->country;
        }
        if($houseIo != null){
            $order->house = $houseIo->house_id;
            $order->comment = $houseIo->comment;
            $order->shipped = date('Y-m-d', strtotime($houseIo->created_at));
        }
        else{
            foreach($_houses as $house){
                if($house->country == $order->country)
                    $order->house = $house->id;
                if($house->country == $order->country && $house->state == $order->state )
                    $order->house = $house->id;
                if($house->country == $order->country && $house->state == $order->state && $house->city == $order->city){
                    $order->house = $house->id;
                    break;
                }
            }
        }
        return view('shipper.orders.order', compact('order', 'houses'));
    }

    public function warehouses(){
        $user = Auth::user();
        if(!$user->can('SHIP_MANAGE')) {
            Session::flash('flash_message', 'Permission required!');
            return view('shipper.warehouses.index');
        }
        $houses = DB::table('cbox_houses')->leftjoin('cbox_houseios', function($join){
                      $join->on('cbox_houses.id', '=', 'cbox_houseios.house_id');
                    })
                    ->where('cbox_houses.del_flg', config('constants.ITEM_IS_LIVE'))
                    ->select('cbox_houses.id', 'cbox_houses.name', 'cbox_houses.manager' , 'cbox_houses.manager_email', 'cbox_houses.address', 'cbox_houses.city', 'cbox_houses.state', 'cbox_houses.country', DB::raw('sum(cbox_houseios.amount) as balance'))
                    ->groupby('cbox_houses.id')->get();
        
        return view('shipper.warehouses.index', compact('houses'));
    }

    public function createWareHouse(){
        return view('shipper.warehouses.create');
    }

    public function createNewHouse(Request $request){
        $param = $request->only(['name', 'manager', 'manager_email', 'address', 'city', 'state', 'country']);
        House::unguard();
        $house = House::create($param);
        House::reguard();
        Session::flash('flash_message', 'WareHouse added!');
        return redirect('shipper/warehouses');
    }

    public function editWareHouse($id){
        $house = House::findOrFail($id);
        return view('shipper.warehouses.edit', compact('house'));
    }

    public function addInventory($id){
        $house = House::findOrFail($id);
        return view('shipper.warehouses.add', compact('house'));
    }

    public function add_remove_inventory($id, Request $request){
        $param = $request->only(['amount', 'comment']);
        $param['house_id'] = $id;
        $param['order_id'] = 0;
        HouseIo::unguard();
        HouseIo::create($param);
        HouseIo::reguard();
        return redirect('shipper/warehouses');
    }

    public function editCurrentHouse($id, Request $request){
        $param = $request->only(['name', 'manager', 'manager_email', 'address', 'city', 'state', 'country']);
        $house = House::findOrFail($id);
        $house->update($param);
        Session::flash('flash_message', 'House updated!');
        return redirect('shipper/warehouses');
    }

    public function deleteHouse($id){
        // House::destroy($id);
        $house = House::findOrFail($id);
        $house->update(['del_flg' => config('constants.ITEM_IS_DELETE')]);

        Session::flash('flash_message', 'House deleted!');

        return redirect('shipper/warehouses');
    }

    public function ship($id, Request $request){
        $param = $request->only(['house', 'comment', 'amount']);
        $order = Order::findOrFail($id);
        $house = House::findOrFail($param['house']);
        $houseParam = [];
        $houseParam['house_id'] = $house->id;
        $houseParam['order_id'] = $id;
        $houseParam['amount'] = -$param['amount'];
        if($param['comment'] == null)
            $param['comment'] = '';
        $houseParam['comment'] = $param['comment'];
        HouseIo::unguard();
        $houseIo = HouseIo::create($houseParam);
        HouseIo::reguard();
        $order->update(['del_flg' => config('constants.BOX_SHIP_DONE')]);
        Session::flash('flash_message', 'Boxes shiped!');
        //If the order is not yet shipped all boxes, create new order
        if($order->amount > $param['amount']){
            $_order['donate_id'] = 0;
            $_order['amount'] = $order->amount - $param['amount'];
            $_order['name'] = $order->name;
            $_order['email'] = $order->email;
            $_order['address'] = $order->address;
            $_order['city'] = $order->city;
            $_order['state'] = $order->state;
            $_order['country'] = $order->country;
            Order::unguard();
            $_order = Order::create($_order);
            Order::reguard();
            Session::flash('flash_message', 'Boxes shiped! A new order has been added for the rest of boxes.');
        }
        $shipped_amount = $param['amount'];
        $mail_data = array('shipped_cnt'=>$param['amount'], 'order'=>$order, 'shipped_amount' => $shipped_amount);
        Mail::send('mail/ship_mail', $mail_data, function($message) use($order, $shipped_amount){
           $message->to($order->email)->subject($shipped_amount.' boxes have been shipped to you.');
           $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        });
        return redirect('shipper/orders');
    }

    public function ioHistory($id){
        $ios = HouseIo::where('house_id', $id)->paginate(10);
        return view('shipper.warehouses.history', compact('ios'));
    }
}
