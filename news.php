<?php
	header("Content-Type:application/json");
	        
			$accountKey = '0WIEUWwNiCrcDqtzpZI9J7tUkGtZs6lYTDQXxzqFI6A';
			$ServiceRootURL = "http://api.datamarket.azure.com/Bing/Search/v1/";
			$WebSearchURL = $ServiceRootURL.'News?$format=json&Query=';
			$context = stream_context_create(array(
					'http' => array(
							'request_fulluri' => true,
							'header' => "Authorization: Basic".base64_encode($accountKey.":".$accountKey)
						)

				));

			$request = $WebSearchURL.urlencode('\''.$_GET['symb'].'\'');
			$response = file_get_contents($request,0,$context);

	        /*$accKey = '0WIEUWwNiCrcDqtzpZI9J7tUkGtZs6lYTDQXxzqFI6A';
            
            $ServiceRootURL='https://api.datamarket.azure.com/Bing/Search/';
                    
                    $WebSearchURL = $ServiceRootURL.'News?$format=json&Query=';
                    
                    $context = stream_context_create(array(
                        'http' => array(
                            'request_fulluri' => true,
                            'header'  => "Authorization: Basic ".base64_encode($accKey.":".$accKey)
                        )
                    ));

                    $request = $WebSearchURL.urlencode( '\'' . $_GET["symb"] . '\'');
                    
                                        
                    $response = file_get_contents($request, 0, $context); */

                    $json_t = json_decode($response);

	echo $json_t;

?>