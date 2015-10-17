<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Envi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function Index()
	{
    $this->load->model( 'Ts_folder_model', 'ts_folder' );
    $file = array( 0 => ( "d:\\dropbox\\ecgs\\test\\assets\\data\\msbas\\EW\\RASTERS\\20030409e.bin.hdr" ) );
    $this->load->library( 'EnviHeader', $file );          
    $enviheader = new EnviHeader( $file );
    $enviheader->read_header();

    $data[ 'col0' ] = 258; $data[ 'row0' ] = 680;
    $data[ 'val0' ] = $enviheader->get_pixel( $data[ 'col0' ], $data[ 'row0' ] );
    
    $data[ 'col' ] = 258; $data[ 'row' ] = 681;
    $data[ 'val' ] = $enviheader->get_pixel( $data[ 'col' ], $data[ 'row' ] );

    $data[ 'col2' ] = 258; $data[ 'row2' ] = 682;
    $data[ 'val2' ] = $enviheader->get_pixel( $data[ 'col2' ], $data[ 'row2' ] );
    
    $this->load->view( 'envi', $data );
	}
}
