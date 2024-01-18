<?php
	include_once 'dbconnection.php';
	class BlogSpace {
		private $id;
		private $user_id;
		private $title;
		private $category;
		private $space_url;
		private $description;
		private $website;
		private $facebook;
		private $twitter;
		private $linkedin;
		private $instagram;
		private $seo_title;
		private $seo_tags;
		private $seo_keywords;
		private $seo_description;
		private $visibility;
		private $created;
		private $updated;
		private $status;
		private $space_image;
		private $space_banner;
		public $dbconn;
		public $database;
		private $table_fields;
		private $table;
		public function __construct()
		{
			$this->database=new DBConnection();
			$this->dbconn=$this->database->DBConnect();
			$this->table="blog_spaces";
			$this->table_fields=array('user_id'=>"required",'title'=>"required",'category'=>"required",'space_url'=>"required|unique",'twitter'=>"",'description'=>"",'website'=>'','facebook'=>"",'linkedin'=>"",'seo_description'=>"",'instagram'=>"",'seo_title'=>"",'seo_tags'=>"",'seo_keywords'=>"",'visibility'=>"required",'created'=>"",'updated'=>"",'status'=>"",'space_image'=>"",'space_banner'=>"");
			$this->user_id=null;$this->title=null;$this->category=null;$this->space_url=null;$this->twitter=null;
			$this->description=null;$this->facebook=null;$this->linkedin=null;$this->seo_description=null;
			$this->instagram=null;$this->seo_title=null;$this->seo_tags=null;$this->seo_keywords=null;$this->id=null;
			$this->visibility=0;$this->created=date('Y-m-d H:i:s');$this->updated=date('Y-m-d H:i:s');;
			$this->status=1;$this->space_image=null;$this->space_banner=null;
		}
		public function isUnique($attribute,$value,$id="")
		{
			$additional_query="";
			if($id!="")
			{
				$additional_query=" AND id!='$id'";
			}
			$query="SEELCT id FROM ".$this->table." WHERE ".$attribute."='$value'".$additional_query;
			$result=mysqli_query($this->dbconn,$query);
			if(mysqli_num_rows($result)>0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		public function is_filled($data)
		{
			$data=trim($data);
			if($data!="")
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		public function validate(array $data)
		{
			foreach($this->table_fields as $key=>$val)
			{
				if($val!="")
				{
					$fields_to_validate[$key]
				}
				
			}
		}
		public function insert(array $fields) 
		{
			foreach($fields as $key=>$val)
			{
				$this->$key=$val;
			}
		}

	}
?>