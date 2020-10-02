<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\HelpersFolder\ApiHelpers;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Orderbook;
use App\Models\ResultModel;
use App\Actions\User\GetUserProfile;
use App\Actions\User\GetOrderForProfileUser;
use App\Actions\Helper\Fail;
use App\Actions\Helper\Success;
use App\Http\Requests\UserRole\UpdateUserRoleRequest;
use App\Actions\User\AddUserRoleAction;
use App\Actions\User\DeleteUserRoleAction;
use App\Actions\User\VerificationUserAction;
class UsersController extends Controller
{
   public function GetUserProfile()
   {
      $response=GetUserProfile::execute();
      if($response)
      {
          return Success::execute('get_data',$response);

      }
      else
      {
        return Fail::execute('get_data_error',$response);

      }

   }

   public function GetUserOrderProfile()
   {
       $response=GetOrderForProfileUser::execute();
       if($response)
       {
           return Success::execute('get_data',$response);

       }
       else
       {
        return Fail::execute('get_data_error',$response);

       }
   }

   public function GetUserOrder()
   {

    $userid=Auth::id();

    $myorder=array();

    $myorder=order::select('id')->where('userid','=',$userid)->get()->toArray();

    $dataset=[];

    $res=array();

    foreach($myorder as  $val)
    {

        $cv1=new ResultModel();

        $dataset=Orderbook::join('Books','Books.id','=','orderbooks.bookid')->
        join('orders','orders.id','=','orderbooks.orderid')->select('Books.title','Books.price')->
        where('orderbooks.orderid','=',$val)->get();

        $cv1=$val;

        $cv1['order_deatils']=[$dataset];

        $res[]=$cv1;



        }

    if($myorder)
        {
            $response=ApiHelpers::creaeApiResponse(false,200,'Get User Profile',$res);
            return response()->json($response,200);
        }
        else
        {
            $response=ApiHelpers::creaeApiResponse(false,400,'Not Found',$cv1);
            return response()->json($response,400);
        }


   }

   public function UpdateUserRole(UpdateUserRoleRequest $requset)
   {
       $response=AddUserRoleAction::execute($requset);
       if ($response && $response!="Not have Admin permission.")
       {
           return Success::execute('User_Role_Updated',$response);

       }
       else
       {
        return Fail::execute('User_Role_Updated_error',$response,501);

       }

   }

   public function DeleteUserRole($userid)
   {
       $response=DeleteUserRoleAction::execute($userid);

       if ($response!=false)
       {
           return Success::execute('User_Role_Deleted',$response);

       }
       else
       {
        return Fail::execute('Not have Admin Permission',$response,501);

       }
    }

    public function VerificationUser($mail)
    {
        $response=VerificationUserAction::execute($mail);
        if($response)
        {
            //return Success::execute('User_Verified',$response);

            return redirect('https://testing.testhis.link/');

        }
        else
        {
            return Fail::execute('User_Verified_error',$response);

        }
    }

}
