<?php
/**
* @class server_status
* @author 스비라 (cs8minty@gmail.com)
* @brief 서버 상태를 출력하는 위젯
**/

class server_status extends WidgetHandler {

  /**
  * @brief 위젯의 실행 부분
  **/

	function proc($args) {

		require_once 'MinecraftQuery.class.php';

		$data = new MinecraftQuery( );

		// 위젯 설정 미입력시, 기본값 설정
		if(!$args->query_port) $args->query_port="25565";

		// 서버 연결
		try
		{
			$data->Connect( $args->server_ip, $args->query_port, 1 );
			$data->info = $data->GetInfo();
			$data->player = $data->GetPlayers();
		}
		catch( MinecraftQueryException $e )
		{
			$data->error = $e->getMessage( );
		}

		// 연결 여부 검사
		if ( !isset( $data->error ) ) {
			$data->isOnline = true;
		} else {
			$data->isOnline = false;
			$data->info[ 'Players' ] = 0;
			$data->info[ 'MaxPlayers' ] = "???";
		}

		$data->server_ip = $args->server_ip;
		$data->avatar_size = $args->avatar_size;
		$data->avatar_site = $args->avatar_site;

		/* 값 반환 */
		Context::set('oData',$data);

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
