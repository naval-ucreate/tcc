<?php  

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class TrelloApi {

    public $api_key='';
    public $client;
    const ApiEndPoint='';
    public $token='';

    public function __construct(String $api_key){
        $this->api_key = $api_key;
        $this->client  =  new Client();
        $this->token= '6ed2a41e48d27d28586223c95939c872f3d0c5396f42a51dff04f373360c1cdc';
        if(Session::get('userinfo')){
            $this->token=Session::get('userinfo')['token'];
        }
    }

    public function getUserInfo(String $token){
        $url        = config("app.trello_api_end_point").'members/me?key='.$this->api_key.'&token='.$token;
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

    public function getUserBoards(Array $option=[]){
        $url  = config("app.trello_api_end_point").'members/me/boards?key='.$this->api_key.'&token='.$this->token;
        if(count($option)>0){
            foreach($option as $key=>$value){
                $url.='&'.$key.'='.$value;
            }
        }
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

    public function GetBoardList(String $board_id,Array $option=[]){
        $url        = config("app.trello_api_end_point").'boards/'.$board_id.'?key='.$this->api_key.'&token='.$this->token;
        $url       .= "&fields=all&lists=all&list_fields=all";
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }

    public function getCardChecklists(string $card_id){
        $url = config("app.trello_api_end_point").'cards/'.$card_id.'/checklists?key='.$this->api_key.'&token='.$this->token;
        $url.="&checkItems=all";
        $response   = $this->client->request('GET',$url);
        if($response->getStatusCode()==200){
            return  json_decode($response->getBody(), true);
        }
        throw new Exception("Api end Error");
    }


}

 