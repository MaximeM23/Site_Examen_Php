<?php
class CMaBD extends CBD
{
	public function __construct()
	{
		parent::__construct("u_exam_pid", "woFyEazlUubxcnni", "michel_maxime_pid_examen");
	}
	
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
?>