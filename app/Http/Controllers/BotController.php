<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function bot($method,$datas=[]){
        $botToken = "6032288848:AAFYw974rvZE7o45fu0JvxbH7g0c9gekZDM";
        $url = "https://api.Telegram.org/bot".$botToken."/".$method;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
        $res = curl_exec($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
        }else{
            return json_decode($res);
        }
    }
    public function index()
    {
        

        $url = "d035b091-d3a3-4aa6-bd9d-839a0416e0f1";

        

        $update = json_decode(file_get_contents('php://input'));
        // $this->bot("sendMessage", [
        //                 'chat_id' => "904664945",
        //                 'text' => $update->message->text??"salom",
        //                 'parse_mode'=>"html",
        //                 "reply_markup"=>json_encode([
        //                     "resize_keyboard"=>true,
        //                     "inline_keyboard"=>[
        //                         [["text"=>"🔥 Ovoz berish 🔥", "web_app"=> [
        //                             "url"=> "https://openbudget.uz/api/v2/vote/mvc/captcha/".$url
        //                         ]],],
        //                     ]
        //                 ]),
        //             ]);
            $message     = $update->message??(object)(['chat'=>(object)['id'=>"904664945"],'text'=>"/start"]);
            $chat_id     = $message->chat->id;
            $text  = $message->text;
    
    
    
    
    
            if($text == "/dev"){
                $this->bot("sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "<b>Dasturchi: Ayxanov - @ayxanov</b>",
                    'parse_mode'=>"html",
                ]);
            }
    
    
    
            if($text == "/ovozlar"){
                $f = file_get_contents("https://openbudget.uz/api/v2/info/initiative/count/".$url);
                $f = json_decode($f, true);
                $t = date("Y.m.d H:i:s", time()+7200);
                $this->bot("sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "<b>📊 Ovozlar:</b> <i>".$f['count']."</i>\n<b>⏰ Vaqt:</b> <i>$t</i>",
                    'parse_mode'=>"html",
                ]);
            }
    
    
            if($text == "/start"){
    
                $this->bot("sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "<b>Assalomu alaykum qadrli qishloqdosh👋🏻</b>",
                    'parse_mode'=>"html",
                    "reply_markup"=>json_encode([
                        "resize_keyboard"=>true,
                        "keyboard"=>[
                            [["text"=>"🔥 Ovoz berish 🔥", "web_app"=> [
                                "url"=> "https://openbudget.uz/api/v2/vote/mvc/captcha/".$url
                            ]],],
                        ]
                    ]),
                ]);
    
                $this->bot("sendPhoto", [
                    'chat_id' => $chat_id,
                    'photo' => "https://api.ekosfera.uz/botrasm.jpg",
                    'caption' => "Openbudget.uz loyihasiga xush kelibsiz!
                    Loyihaning asosiy maqsadi qishloqni asfalt qilish.
                    ",
                    'parse_mode'=>"html",
                    "reply_markup"=>json_encode([
                        "inline_keyboard"=>[
                            [[
                                "text"=> "🔥 Ovoz berish 🔥",
                                "web_app"=> [
                                    "url"=> "https://openbudget.uz/api/v2/vote/mvc/captcha/".$url
                                ]]
                            ],
                        ]
                    ]),
                ]);
            }
    
    
            if($text == "/ovozberish"){
                $this->bot("sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "<b>Ovoz berishni tanlang👇</b>",
                    'parse_mode'=>"html",
                    "reply_markup"=>json_encode([
                        "resize_keyboard"=>true,
                        "inline_keyboard"=>[
                            [["text"=>"🔥 Ovoz berish 🔥", "web_app"=> [
                                "url"=> "https://openbudget.uz/api/v2/vote/mvc/captcha/".$url
                            ]],],
                        ]
                    ]),
                ]);
            }
    
    
            if($text == "/info"){
                $this->bot("sendMessage", [
                    'chat_id' => $chat_id,
                    'text' => "Shunchaki pastda ko'rsatilganlarni bajaring👇
    
                    1. Avvalo telefon raqamingizni kiritasiz, 
                    2. Rasmda ko'rsatilgan matematik misolni yechasiz
                    3. Raqamingizga kelgan smsni kerakli joyga kiritasiz
    
                    <b>Ovoz berishni boshlash👇</b>",
                    'parse_mode'=>"html",
                    "reply_markup"=>json_encode([
                        "resize_keyboard"=>true,
                        "inline_keyboard"=>[
                            [["text"=>"🔥 Ovoz berish 🔥", "web_app"=> [
                                "url"=> "https://openbudget.uz/api/v2/vote/mvc/captcha/".$url
                            ]],],
                        ]
                    ]),
                ]);
            }
    
    
    
            if($text == "/loyihahaqida"){
    
                $rt = file_get_contents("https://openbudget.uz/api/v1/initiatives/".$url);
                $rt = json_decode($rt, true);
    
                $f = file_get_contents("https://openbudget.uz/api/v2/info/initiative/count/".$url);
                $f = json_decode($f, true);
                $text = "\n<b>🔹 Ovozlar soni:</b> <i>".$f['count']."</i>";
                $text .= "\n\n🔹 <b>Tashabbus:</b> <i>".$rt['board']['title']."</i>";
                $text .= "\n🔹 <b>Viloyat:</b> <i>".$rt['region_title']."</i>";
                $text .= "\n🔹 <b>Tuman:</b> <i>".$rt['district_title']."</i>";
                $text .= "\n🔹 <b>Mahalla:</b> <i>".$rt['quarter_title']."</i>";
                $text .= "\n🔹 <b>Ko'cha:</b> <i>".$rt['description']."</i>";
                $text .= "\n🔹 <b>Viloyat:</b> <i>".$rt['region_title']."</i>";
                $text .= "\n🔹 <b>Loyiha yaratuvchisi:</b> <i>".$rt['author_fullname']."</i>";
                $text .= "\n🔹 <b>So'ralgan mablag':</b> <i>".$rt['granted_amount']."</i>";
    
                $t = date("Y.m.d H:i:s", time()+7200);
    
                $text .= "\n\n<b>🔹 Vaqt:</b> <i>$t</i>";
    
                $this->bot("sendPhoto", [
                    'chat_id' => $chat_id,
                    'photo' => "https://telegra.ph/file/b5b3d9284fa219ae986ad.png",
                    'caption' => $text,
                    'parse_mode'=>"html",
                ]); 
        }
        
    }
}
