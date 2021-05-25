<?php

namespace Salesforce;

use Http\HttpHeader;

class OAuth {

    public static function start($config, $flowName, $domain = "default"){

        $flow = $config->getFlowConfig($flowName, $domain);
        return $flowName == "webserver" ? self::newOAuthResponse($config, $flow) : OAuthRequest::newAccessTokenRequest($config, $flow);
    }


    public static function newOAuthResponse(\OAuthConfig $config, \OAuthFlowConfig $flow) {

        $resp = new OAuthResponse();

        $url = $flow->getAuthorizationUrl();  // Since this is a web server oauth, there will be two oauth urls in the config.

        //$state = array("connected_app_name" => $config->getName(), "flow" => $flow->getName(), "domain" => $flow->getDomain());

        $body = array(
            "client_id"		=> $config->getClientId(),
            "redirect_uri"	=> $flow->getCallbackUrl(),
            "response_type" => "code",
            "state"         => self::encodeState($config->getName(), $flow->getName(), $flow->getDomain())
        );

        $url .= "?" . http_build_query($body);

        $resp->addHeader(new HttpHeader("Location", $url));

        return $resp;
    }

    public static function encodeState($connectedAppName, $flow, $domain){

        $state = array("connected_app_name" => $connectedAppName, "flow" => $flow, "domain" => $domain);

        return json_encode($state);

        
    }

    public static function setSession($connectedApp, $flow, $instanceUrl, $accessToken, $refreshToken = null){

        if($refreshToken != null) \Session::set($connectedApp, $flow, "refresh_token", $refreshToken);
        \Session::set($connectedApp, $flow, "instance_url", $instanceUrl);
        \Session::set($connectedApp, $flow, "access_token", $accessToken);
        \Session::set($connectedApp, $flow, "userId", OAuth::getUserId($connectedApp, $flow));
    }

    public static function getUserId($connectedApp, $flow){

		$accessToken = \Session::get($connectedApp, $flow, "access_token");
		$instanceUrl = \Session::get($connectedApp, $flow, "instance_url");

		$url = "/services/oauth2/userinfo?access_token={$accessToken}";

		$req = new RestApiRequest($instanceUrl, $accessToken);

		$resp = $req->send($url);

		$userInfo = $resp->getBody();
		
		return $userInfo["user_id"];
	}
}