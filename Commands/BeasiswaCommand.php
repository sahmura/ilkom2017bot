<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class BeasiswaCommand extends UserCommand
{
    protected $name = 'beasiswa';                      // Your command's name
    protected $description = 'List Info Beasiswa Terbaru'; // Your command description
    protected $usage = '/info [id:optional]';                    // Usage of your command
    protected $version = '1.0.0';                  // Version of your command

    public function execute()
    {
        $message = $this->getMessage();            // Get Message object

        $chat_id = $message->getChat()->getId();   // Get the current Chat ID
        $from_id = $message->getFrom()->getId();
        $nama = $message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName();
        $id = trim($message->getText(true));
        
        if($id === ''){
            
            $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/beasiswa/all/{APIKEY}');
            $decdata = json_decode($data, true);
            
            $koneksi = $decdata['koneksi'];
            $status = $decdata['status'];
            $info = $decdata['data'];
            
            if($koneksi == true){
                
                if($status == 'Success'){
                    
                    $text = "Hai $nama. Berikut ini adalah info-info beasiswa yang ada\n\n";
                    $i = 1;
                    foreach ($info as $datainfo) :
                        $text .= "<b>$i.  " . $datainfo['judul'] . "\n</b>";
                        $text .= "Pada  " . $datainfo['date'] . " WIB\n";
                        $text .= "Selengkapnya ketik : <b>/beasiswa ".$datainfo['id']."</b>\n\n";
                        $i++;
                    endforeach;
                    $text .= "\n\nTambah info beasiswa dengan perintah /addbeasiswa";
                    
                    $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                    
                     return Request::sendMessage($kirimpesan); 
                    
                    
                }
                
                if($status == 'NotFound'){
                    
                    $text = "Hai $nama. Tidak ada info beasiswa untuk saat ini.";
                    $text .= "\n\nTambah info beasiswa dengan perintah /addbeasiswa";
                    
                    $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                    
                    return Request::sendMessage($kirimpesan); 
                }
            }
            
            if($koneksi == false){
                
                $text = "Hai $nama. Terjadi kesalahan dalam <b>pengambilan data</b>. Silahkan coba beberapa saat lagi.";
                $text .= "\n\nTambah info beasiswa dengan perintah /addbeasiswa";
                
                $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                    
                return Request::sendMessage($kirimpesan);
            }
        
        }
        
        else {
            
            $data = file_get_contents('http://ilkomunnes.000webhostapp.com/api/beasiswa/'.$id.'/{APIKEY}');
            $decdata = json_decode($data, true);
            
            $koneksi = $decdata['koneksi'];
            $status = $decdata['status'];
            $info = $decdata['data'][0];
            
            if($koneksi == true){
                
                if($status == 'Success'){
                    
                        $text .= "<b>" . $info['judul'] . "\n</b>";
                        $text .= "Pada  " . $info['date'] . " WIB\n\n";
                        $text .= $info['pesan']."\n\n";
                        $text .= "Diposting oleh : " . $info['dari'];
                    
                    $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                    
                     return Request::sendMessage($kirimpesan); 
                    
                    
                }
                
                if($status == 'NotFound'){
                    
                    $text = "Hai $nama. Info beasiswa tidak ditemukan. Pastikan menggunakan perintah dibawah ini\n\n";
                    $text .= $this->getUsage();
                    
                    $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                    
                    return Request::sendMessage($kirimpesan); 
                }
            }
            
            if($koneksi == false){
                
                $text = "Hai $nama. Terjadi kesalahan dalam <b>pengambilan data</b>. Silahkan coba beberapa saat lagi.";
                
                $kirimpesan = [
                            'chat_id' => $chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ];
                    
                return Request::sendMessage($kirimpesan);
            }
        }

        
    }
}
