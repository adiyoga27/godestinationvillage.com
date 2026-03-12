<?php
 
namespace App\Services\Midtrans;
 
use Midtrans\Snap;
 
class CreateSnapTokenService extends Midtrans
{
    protected $order;
 
    public function __construct($order)
    {
        parent::__construct();
 
        $this->order = $order;
    }
 
    public function getSnapToken($order)
    {
        
 
        $snapToken = Snap::getSnapToken($order);
 
        return $snapToken;
    }

    
}
