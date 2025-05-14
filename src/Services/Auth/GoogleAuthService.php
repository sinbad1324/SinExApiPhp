<?php 
namespace Services\Auth;

use Entities\UserEntities;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Oauth2;
use Models\UserModel;
use Traits\DataFormatTrait;
use Traits\StringShuffle;
require_once "../src/Traits/DataFormatTrait.php";
require_once "../src/Traits/StringShuffle.php";
require_once "../src/Models/UserModel.php";
class GoogleAuthService {
    private Client $client ;
    use DataFormatTrait,StringShuffle;

    function __construct()
    {
        $this->Init();
    }

    private  function Init(){
            $this->client= new Client();
            $this->client->setApplicationName("SinEx");
            $this->client->setClientId($_ENV['GOOGLE_ID']);   
            $this->client->setClientSecret($_ENV['GOOGLE_SECRET']); 
            $this->client->addScope('email');
            $this->client->addScope('profile');
            $this->client->setRedirectUri("http://localhost:5173/auth/google-connection");
            $this->client->setAccessType('online');
            $this->client->setIncludeGrantedScopes(true);   
    }
    
    public  function GetURLForClient() :string {
        $this->Init();
        return $this->client->createAuthUrl();
    }

    public  function Connection($data) {
        // le token puis se connecté
        $client = $this->client;
        $access_token = $client->fetchAccessTokenWithAuthCode($data["code"]);
        if (isset($access_token["error"])) {
            return static::json("Il y a eu un probléme lors de la creation de votre compte le code d'authethification est faux! ");
        }
        $client->setAccessToken($access_token);
        // avoir les infos avec l'api Oauth2!
        $oauth2 = new Oauth2($client);
        $userInfo = $oauth2->userinfo->get();
        $email = $userInfo->email;
        $name = $userInfo->name."_".$userInfo->givenName;
        $googleId = $userInfo->id;
        //Verifie si il y a deja un utilisateur avec cette google id si oui on le connect directement
        $ud = UserModel::FindWithGoogleId($googleId);
        if($ud){
            //Rediriger l'utlisateur
            $user = new UserEntities($ud);
            return static::json("Vous etes connecté!" , ["JWT"=> $user->CreateJWTforUser(),"userData"=>["userName"=>$user->GetName(),"userId"=>$user->GetId()]] ,true);
        }
        //Si on arrive juste qu'a ici c'es que le utilisateur n'a pas de compte
        // alors on verifie que le nom n'est pas deja pris
        if (UserModel::FindWithName($name)) {
            $name = "User_".random_int(1,2000);
        }
        // verifier que le mail n'est pas pris
        if (UserModel::FindWithEmail($email)) 
            return static::json("Cette email existe deja dans notre base de donnée",[]);
        // toutes les condition remplis nous allons créé un nouveau utilisateur: 
        $newUser=UserModel::CreateOAuthUser([
            "userName"=> $name,
            "email"=>$email,
            "googleId"=>$googleId,
        ]);
        if ($newUser) {
            // rediriger le uilisateur
            $user = new UserEntities(UserModel::GetLastUserAdded());
            return static::json("Vous etes connecté!" , ["JWT"=> $user->CreateJWTforUser(),"userData"=>["userName"=>$user->GetName(),"userId"=>$user->GetId()]] ,true);
        }
        return static::json("Il y a eu un probléme lors de la creation de votre compte!",$access_token);
    }

}


?>