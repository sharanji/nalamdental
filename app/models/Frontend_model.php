<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend_model extends CI_Model 
{
    public function __construct() 
	{
        parent::__construct();
    }

    #General settings
    function get_frontend_general_settings($type = '') 
	{
		$result = $this->db->get_where('frontend_general_settings', array('type' => $type))->row()->description;
		return $result == null ? '' : $result;
    }

    #Send message from contact form
    function send_contact_message() 
	{
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$address = $this->input->post('address');
		$comment = $this->input->post('comment');

		$receiver_email = $this->db->get_where('frontend_general_settings', array(
			'type' => 'email'
		))->row()->description;

		$msg = $comment."</br>";
		$msg .= $first_name." ".$last_name;
		$msg .= "Phone : ".$phone;
		$msg .= "Address : ". $address;
		$this->email_model->contact_message_email($email, $receiver_email, $msg);
    }

    # Update slider images
    function update_slider_images() 
	{
		$current_images_json = $this->db->get_where('frontend_general_settings', array('type' => 'slider_images'))->row()->description;
		$current_images = json_decode($current_images_json);
		$slider = array();
		for ($i=0; $i < 3; $i++) 
		{
			$image = $current_images[$i]->image;
			$data['title']  = $this->input->post('title_'.$i);
			$data['description']  = $this->input->post('description_'.$i);
			if ($_FILES['slider_image_'.$i]['name'] != '') {
				$data['image']  = $_FILES['slider_image_'.$i]['name'];
			} else {
				$data['image']  = $image;
			}
			array_push($slider, $data);
			move_uploaded_file($_FILES['slider_image_'.$i]['tmp_name'], 'uploads/frontend/slider/'. $_FILES['slider_image_'.$i]['name']);
		}
		$images['description']  = json_encode($slider);
		$this->db->where('type', 'slider_images');
		$this->db->update('frontend_general_settings', $images);
    }

    # Update general settings
    function updateSocialMediaSettings() 
	{
		$data['description']  = $this->input->post('school_title');
		$this->db->where('type', 'school_title');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('email');
		$this->db->where('type', 'email');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('phone');
		$this->db->where('type', 'phone');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('fax');
		$this->db->where('type', 'fax');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('copyright_text');
		$this->db->where('type', 'copyright_text');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('address');
		$this->db->where('type', 'address');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('school_location');
		$this->db->where('type', 'school_location');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('homepage_note_title');
		$this->db->where('type', 'homepage_note_title');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('homepage_note_description');
		$this->db->where('type', 'homepage_note_description');
		$this->db->update('frontend_general_settings', $data);

		$data['description']  = $this->input->post('recaptcha_site_key');
		$this->db->where('type', 'recaptcha_site_key');
		$this->db->update('frontend_general_settings', $data);

		$links = array();
		$social['facebook'] = $this->input->post('facebook');
		$social['twitter'] = $this->input->post('twitter');
		$social['linkedin'] = $this->input->post('linkedin');
		$social['google'] = $this->input->post('google');
		$social['youtube'] = $this->input->post('youtube');
		$social['instagram'] = $this->input->post('instagram');
		
		array_push($links, $social);
		$data['description']  = json_encode($links);
		$this->db->where('type', 'social_links');
		$this->db->update('frontend_general_settings', $data);
	}
	
	# Get general settings
    function settings($type = '') 
	{
		$result = $this->db->get_where('settings', array('type' => $type))->row()->description;
		return $result == null ? '' : $result;
    }
	
	function getCMS()
	{
		$result = $this->db->query("select * from cms where cms_status=1")
				->result_array();
		return $result;
	}
	
	function getCMSpages($id="")
	{
		$result = $this->db->query("select * from cms where cms_id='".$id."' ")->result_array();
		return $result;
	}
	
	# Gallery
    function get_gallaries() 
	{
		$this->db->order_by('date_added', 'DESC');
		$result = $this->db->get('frontend_gallery')->result_array();
		return $result;
    }

    function get_gallery_info_by_id($gallery_id="") 
	{
		$this->db->where('frontend_gallery_id', $gallery_id);
		$result = $this->db->get('frontend_gallery')->result_array();
		return $result;
    }

    function add_gallery() 
	{
		$data['title']            = $this->input->post('title');
		$data['description']      = $this->input->post('description');
		$data['show_on_website']  = $this->input->post('show_on_website');
		$data['date_added']       = strtotime($this->input->post('date_added'));
		if ($_FILES['cover_image']['name'] != '') 
		{
			$data['image']  = $_FILES['cover_image']['name'];
			move_uploaded_file($_FILES['cover_image']['tmp_name'], 'uploads/frontend/gallery_cover/'. $_FILES['cover_image']['name']);
		}
		$this->db->insert('frontend_gallery', $data);
    }

    function edit_gallery($gallery_id) 
	{
		$image = $this->db->get_where('frontend_gallery', array('frontend_gallery_id' => $gallery_id))->row()->image;
		$data['title']            = $this->input->post('title');
		$data['description']      = $this->input->post('description');
		$data['show_on_website']  = $this->input->post('show_on_website');
		$data['date_added']       = strtotime($this->input->post('date_added'));
		if ($_FILES['cover_image']['name'] != '') 
		{
			$data['image']  = $_FILES['cover_image']['name'];
			move_uploaded_file($_FILES['cover_image']['tmp_name'], 'uploads/frontend/gallery_cover/'. $_FILES['cover_image']['name']);
		} 
		else 
		{
			$data['image']  = $image;
		}
		$this->db->where('frontend_gallery_id', $gallery_id);
		$this->db->update('frontend_gallery', $data);
    }

    function add_gallery_images($gallery_id) 
	{
        $files = $_FILES;
        $number_of_images = count($_FILES['gallery_images']['name']);
        for ($i=0; $i < $number_of_images; $i++) 
		{
			if ($files['gallery_images']['name'][$i] != '') 
			{
				move_uploaded_file($files['gallery_images']['tmp_name'][$i], 'uploads/frontend/gallery_images/'. $files['gallery_images']['name'][$i]);
				$data['frontend_gallery_id']  = $gallery_id;
				$data['image']  = $files['gallery_images']['name'][$i];
				$this->db->insert('frontend_gallery_image', $data);
			}
        }
    }
	
	# Model end here
}
