<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Dish;
use Braintree\Gateway;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $newOrder = Order::create([
            'firstname' => $data['name'],
            'lastname' => $data['surname'],
            'email' => $data['mail'],
            'address' => $data['address'],
            'amount' => $request->store['totalPrice'],
            'delivery_state' => 0,
            'restaurant_id' => $data['store']['cartDish'][0]['restaurant_id']
        ]);

        //ciclo su quello che contiene il cartDish che contiene elementi del tipo:
        //$data['store']['cartDish'][0] = id: 8, price: "20.00", restaurant_id: 27, title: "Pizza Margherita"
        for ($i = 0; $i < count($data['store']['cartDish']); $i++) {
            //per ogni iterazione vado a prendere nella tabella Dishes il piatto con id corrispondente all'i dell'i-esimo piatto passato da front-end(il ->first() è necessario se no ritorna un array e non un oggetto)
            $singleDish = Dish::where('id', '=', $data['store']['cartDish'][$i]['id'])->get()->first();

            //va ad inserire nella tabella ponte la coppia di id dell'ordine e del piatto e nella colonna quantity aggiunge il 'count' di ogni piatto
            $singleDish->orders()->attach($newOrder, ['quantity' => $data['store']['cartDish'][$i]['count']]);
        }

        return response()->json([
            'results' => $this->generate()
        ]);
    }

    public function generate()
    {
        //comando "composer php composer.phar install"
        $gateway = new Gateway([
            'environment' => 'sandbox',
            'merchantId' => 'jtzyx9ysfdj22xnc',
            'publicKey' => 'ntf7wr6hzs78r63f',
            'privateKey' => '89dd655333aa01c4a4f5425ffd2cac72'
        ]);

        $token = $gateway->clientToken()->generate();

        return $token;
    }
}
