<?php
/**
* @class server_status
* @author 스비라 (cs8minty@gmail.com)
* @brief 서버 상태를 출력하는 위젯
* @version 0.1
**/

class server_status extends WidgetHandler {

  /**
  * @brief 위젯의 실행 부분
  **/

	function proc($args) {
		
		if(!$args->query_port) $args->query_port="25565";
    
		if ( !defined('__DIR__') ) {
		  define('__DIR__', dirname(__FILE__));
		}
			
		require __DIR__ .'/MinecraftQuery.class.php';
			
		$data = new MinecraftQuery();

		// 서버 연결, 타임아웃 5
		try {
			$data->Connect( $args->server_ip, $args->query_port, 5 );
			$players = $data->GetPlayers();
			$player = array('username' => $player);
			$info = $data->GetInfo();
		} catch( MinecraftQueryException $e ) {
			$Error = $e->getMessage( );
		}
		
		// 연결 여부 검사
		if (!isset($Error)) {
			$server_online = "Online";
		} else {
			$server_online = "Offline";
			$info['Players'] = 0;
			$info['MaxPlayer'] = "???";
		}
			
		//값 반환
		Context::set('oMaxPlayers',$info['MaxPlayers']);
		Context::set('oCurrentPlayer',$info['Players']);
		Context::set('oServerStatus',$server_online);
		Context::set('oPlayer',$players);
		
		Context::set('oAvatarSize',$args->avatar_size);
		Context::set('oAvatarSite',$args->avatar_site);
		
		Context::set('oServerIp',$info['HostIp']);
		Context::set('oServerPort',$info['HostPort']);
		Context::set('oServerName',$info['HostName']);
		Context::set('oSoftware',$info['Software']);
		Context::set('oVersion',$info['Version']);
		Context::set('oGameType',$info['GameType']);
		Context::set('oPlugins',$info['Plugins']);
		Context::set('oRawPlugins',$info['RawPlugins']);
		Context::set('oMap',$info['Map']);

		// 템플릿의 스킨 경로를 지정 (skin, colorset에 따른 값을 설정)
		$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);

		// 템플릿 파일을 지정
		$tpl_file = 'view';

		// 템플릿 컴파일
		$oTemplate = &TemplateHandler::getInstance();
		return $oTemplate->compile($tpl_path, $tpl_file);
	}
}

?>
