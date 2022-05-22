<?php

namespace Salesforce;

use Http\HttpHeader;
use Http\HttpRequest as HttpRequest;



class OAuth {


    /**
     * @method start
     * 
     * @description Start an OAuth 2.0 flow.
     * Currently this class supports the OAuth username/password and OAuth webserver flows.
     * The username/password flow starts with an HTTP Request; the webserver flow starts with an HTTP Response,
     * which should redirect the user-agent to the identity provider's login page.
     * The Username/password flow should be able to use application-level variables (if desired);
     * The Webserver flow should use session-level variables.
     */
    public static function start($config, $flow){

        return $flow == "webserver" ? self::newOAuthResponse($config,$flow) : self::newOAuthRequest($config,$flow);
    }



	public static function newOAuthRequest($config, $flow) {

		$flowConfig = $config->getFlowConfig($flow);

		if($flowConfig->getTokenUrl() == null){

			throw new \Exception("null token url");
		}

		$req = new OAuthRequest($flowConfig->getTokenUrl());

		$body = array(
			"grant_type" 			=> "password",
			"client_id" 			=> $config->getClientId(),
			"client_secret"			=> $config->getClientSecret(),
			"username"				=> $flowConfig->getUserName(),
			"password"				=> $flowConfig->getPassword() . $flowConfig->getSecurityToken()
		);

		$body = http_build_query($body);
		$contentType = new HttpHeader("Content-Type", "application/x-www-form-urlencoded");
		$req->addHeader($contentType);
		
		$req->setBody($body);
		$req->setMethod("POST");
		// Sending a HttpResponse class as a Header to represent the HttpResponse.
		$req->addHeader(new HttpHeader("X-HttpClient-ResponseClass","\Salesforce\OAuthResponse"));

		return $req;
	}


    // This is step one.  We are going to make a request to the "auth_url".
    // We do this by redirecting the user agent to the auth_url.
    public static function newOAuthResponse($config,$flow) {

        $flowConfig = $config->getFlowConfig($flow);

        $resp = new OAuthResponse();

        $url = $flowConfig->getAuthorizationUrl();  // Since this is a web server oauth, there will be two oauth urls in the config.

        $state = array("connected_app_name" => $config->getName(), "flow" => $flow);

        $body = array(
            "client_id"		=> $config->getClientId(),
            "redirect_uri"	=> $flowConfig->getAuthorizationRedirect(),
            "response_type" => "code",
            "state"         => json_encode($state)
        );

        $url .= "?" . http_build_query($body);

        $resp->addHeader(new HttpHeader("Location", $url));

        return $resp;
    }


}