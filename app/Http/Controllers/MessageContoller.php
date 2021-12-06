<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageHeader;
use App\Models\MessageDetail;
use App\Models\User;
use Carbon\Carbon;

class MessageContoller extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            //check message header
            $get_message = MessageHeader::where('from', $request->id)->where('to', $request->to)->first();

            if($get_message == null){

                //create code message
                $get_code = MessageHeader::orderBy('code', 'desc')->first();

                if($get_code == null){
                    $code = 'M00001';
                }else{
                    $sub_str = substr($data_book->code,3,5);
                    $count = str_pad((int)$sub_str + 1, 5, "0", STR_PAD_LEFT);
                    $code = 'M'.$count;
                }

                $array_header = array(
                    'code'         => $code,
                    'from'         => $request->id,
                    'to'           => $request->to,
                    'last_message' => $request->message,
                    'created_at'   => Carbon::now()
                );

                $array_detail = array(
                    'code_header'  => $code,
                    'message'      => $request->message,
                    'placement'    => 'left',
                    'created_at'   => Carbon::now()
                );

                MessageHeader::insert($array_header);
                MessageDetail::insert($array_detail);
            }else{
                //data is already in header
                $array_header = array(
                    'last_message' => $request->message,
                    'updated_at'   => Carbon::now()
                );

                $array_detail = array(
                    'code_header'  => $get_message->code,
                    'message'      => $request->message,
                    'placement'    => 'left',
                    'created_at'   => Carbon::now()
                );

                MessageHeader::where('from', $request->id)->where('to', $request->to)->update($array_header);
                MessageDetail::insert($array_detail);
            }

            return [
                'message' => 'success',
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        
    }

    public function getMessage(Request $request)
    {
        try {
            $get = MessageHeader::where('from', $request->id)
                                ->orWhere('to', $request->id)->get();

            $data = [];
            foreach ($get as $key => $value) {
                $get_detail = MessageDetail::where('code_header', $value->code)->get();

                if($value->from == $request->id){
                    $user = User::where('id', $value->to)->first();
                    $variable_user = 'to';
                }else{
                    $user = User::where('id', $value->from)->first();
                    $variable_user = 'from';
                }

                $data[] = array(
                            'code'          => $value->code,
                            $variable_user  => $user->name,
                            'details'       => $get_detail
                );
            }

            return [
                'message' => 'success',
                'status' => 200,
                'data'   => $data
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        
    }

    public function replyMessage(Request $request)
    {
        try {
            $get_message = MessageHeader::where('to', $request->id)->where('from', $request->to)->first();

            $array_header = array(
                'last_message' => $request->message,
                'updated_at'   => Carbon::now()
            );

            $array_detail = array(
                'code_header'  => $get_message->code,
                'message'      => $request->message,
                'placement'    => 'right',
                'created_at'   => Carbon::now()
            );

            MessageHeader::where('to', $request->id)->where('from', $request->to)->update($array_header);
            MessageDetail::insert($array_detail);

            return [
                'message' => 'success',
                'status' => 200
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        
    }

    public function getAllMessage(Request $request)
    {
        try {
            $get = MessageHeader::where('from', $request->id)
                                ->orWhere('to', $request->id)->get();

            $data = [];
            foreach ($get as $key => $value) {

                if($value->from == $request->id){
                    $user = User::where('id', $value->to)->first();
                    $variable_user = 'to';
                }else{
                    $user = User::where('id', $value->from)->first();
                    $variable_user = 'from';
                }

                $data[] = array(
                            'code'              => $value->code,
                            'conversation to'   => $user->name,
                            'last_message'      => $value->last_message
                );
            }

            return [
                'message' => 'success',
                'status' => 200,
                'data'   => $data
            ];
        } catch (Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        
    }
}
