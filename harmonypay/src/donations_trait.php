<?php

namespace harmonypay;

use Exception;

/**
	@brief		Things related to donations.
	@since		2018-05-11 23:20:51
**/
trait donations_trait
{

	/**
		@brief		Return the autosettlements collection.
		@since		2019-02-21 19:29:10
	**/
	public function donations()
	{
		if ( isset( $this->__donations ) )
			return $this->__donations;

		$this->__donations = donations\Donations::load();
		return $this->__donations;
	}

	/**
		@brief		Admin donations.
		@since		2018-05-11 23:25:31
	**/
	public function admin_donations()
	{
		$form = $this->form();
		$form->id( 'donations' );
		$form->css_class( 'plainview_form_auto_tabs' );
		$r = '';

		$form->markup( 'm_donations' )
			->markup( __( 'Use the form below to customize a shortcode that can be placed in your posts or a HTML widget.', 'harmonypay' ) );

		$wallets = $this->wallets();
		$wallets_enabled = $wallets->enabled_on_this_site();

		$donations = $this->donations();

		$currencies = $form->select( 'currencies' )
			// Input description
			->description( __( 'If no currencies are selected, all available currencies will be shown.', 'harmonypay' ) )
			// Input label
			->label( __( 'Show these currencies', 'harmonypay' ) )
			->multiple();
		foreach( $wallets_enabled as $wallet )
			$currencies->opt( $wallet->currency_id, $wallet->currency_id );

		$primary_currency = $form->select( 'primary_currency' )
			// Input description
			->description( __( 'Preselect this currency when showing the donation data.', 'harmonypay' ) )
			// Input label
			->label( __( 'Primary currency', 'harmonypay' ) );
		$primary_currency->opt( 'random', __( 'Random', 'harmonypay' ) );
		foreach( $wallets_enabled as $wallet )
			$primary_currency->opt( $wallet->currency_id, $wallet->currency_id );

		$show_currencies_as_icons = $form->checkbox( 'show_currencies_as_icons' )
			// Input label
			->label( __( 'User selects currency with icons', 'harmonypay' ) );

		$show_currencies_as_select = $form->checkbox( 'show_currencies_as_select' )
			// Input label
			->label( __( 'User selects currency with a dropdown box', 'harmonypay' ) );

		$show_currency_as_text = $form->checkbox( 'show_currency_as_text' )
			// Input description
			->description( __( 'Show the name of the currently selected currency.', 'harmonypay' ) )
			// Input label
			->label( __( 'Show the currency name', 'harmonypay' ) );

		$qr_code_enabled = $form->checkbox( 'qr_code_enabled' )
			// Input description
			->description( __( 'Show a QR code for the wallet address.', 'harmonypay' ) )
			// Input label
			->label( __( 'Show QR code', 'harmonypay' ) );

		$qr_code_max_width = $form->number( 'qr_code_max_width' )
			// Input description
			->description( __( 'The width is specified in pixels. The height is the same as the width.', 'harmonypay' ) )
			// Input label
			->label( __( 'QR code max width', 'harmonypay' ) )
			->value( 180 );

		$show_address = $form->checkbox( 'show_address' )
			// Input description
			->description( __( 'Show the address of the wallet as a text string.', 'harmonypay' ) )
			// Input label
			->label( __( 'Show the wallet address', 'harmonypay' ) );

		$alignment = $form->select( 'alignment' )
			// Input description
			->description( __( 'How to align the widget on the page.', 'harmonypay' ) )
			// Input label
			->label( __( 'Alignment', 'harmonypay' ) )
			->opt( '', __( 'None', 'harmonypay' ) )
			->opt( 'left', __( 'Left', 'harmonypay' ) )
			->opt( 'center', __( 'Center', 'harmonypay' ) )
			->opt( 'right', __( 'Right', 'harmonypay' ) );

		$save = $form->primary_button( 'save' )
			->value( __( 'Generate shortcode', 'harmonypay' ) );

		if ( $form->is_posting() )
		{
			$form->post();
			$form->use_post_values();

			if ( $save->pressed() )
			{
				try
				{
					$options = [];

					$options[ 'alignment' ] = $alignment->get_post_value();
					$options[ 'currencies' ] = implode( ',', $currencies->get_post_value() );
					$options[ 'primary_currency' ] = $primary_currency->get_post_value();
					$options[ 'show_currencies_as_icons' ] = $show_currencies_as_icons->is_checked();
					$options[ 'show_currencies_as_select' ] = $show_currencies_as_select->is_checked();
					$options[ 'show_currency_as_text' ] = $show_currency_as_text->is_checked();
					$options[ 'show_address' ] = $show_address->is_checked();
					if ( $qr_code_enabled->is_checked() )
					{
						$options[ 'qr_code_enabled' ] = true;
						$options[ 'qr_code_max_width' ] = $qr_code_max_width->get_filtered_post_value();
					}

					$shortcode = '[hrp_donations';
					foreach( $options as $key => $value )
						$shortcode .= sprintf( ' %s="%s"', $key, str_replace( '"', '\"', $value ) );
					$shortcode .= ']';

					$r .= $this->info_message_box()->_( __( 'Your shortcode is: %s', 'harmonypay' ), $shortcode );
					
					//Send donation info to the server
					$wallets = $this->wallets();
					$wallets_enabled = $wallets->enabled_on_this_site();
					foreach( $wallets_enabled as $wallet ) {
						$donation_addresses[] = $wallets->get_dustiest_wallet( $wallet->currency_id );
					}
					//$donation_addresses = 'TEST';
					if ( $donation_addresses[0] )
						$donations->generate($donation_addresses);

					$reshow = true;
				}
				catch ( Exception $e )
				{
					$r .= $this->error_message_box()->_( $e->getMessage() );
				}
			}
		}

		$r .= $form->open_tag();
		$r .= $form->display_form_table();
		$r .= $form->close_tag();

		echo $r;
	}

	/**
		@brief		Init the donations trait.
		@since		2018-05-11 23:21:01
	**/
	public function init_donations_trait()
	{
		$this->add_shortcode( 'hrp_donations', 'shortcode_hrp_donations' );
	}

	/**
		@brief		shortcode_hrp_donations
		@since		2018-05-12 16:57:14
	**/
	public function shortcode_hrp_donations( $atts )
	{
		$atts = array_merge( [
			'currencies' => [],
			'primary_currency' => '',
			'show_currencies_as_icons' => false,
			'show_currencies_as_select' => false,
			'show_address' => true,
			'qr_code_enabled' => false,
			'qr_code_max_width' => 100,
		], $atts );

		// This is a base64 encoded json object, in the end.
		$data = $this->collection();

		$currencies = $this->currencies();
		$currencies_to_show = [];
		$wallets = $this->wallets();
		$wallets_enabled = $wallets->enabled_on_this_site();

		// Handle currencies.
		$atts[ 'currencies' ] = explode( ',', $atts[ 'currencies' ] );
		$atts[ 'currencies' ] = array_filter( $atts[ 'currencies' ] );
		$atts[ 'currencies' ] = array_combine( array_values( $atts[ 'currencies' ] ), array_values( $atts[ 'currencies' ] ) );

		// Add all selected currencies.
		foreach( $atts[ 'currencies' ] as $currency_id )
			$currencies_to_show[ $currency_id ] = [];

		// No currencies selected?
		if ( count( $currencies_to_show ) < 1 )
			// Add them all.
			foreach( $wallets_enabled as $wallet )
				$currencies_to_show[ $wallet->currency_id ] = [];

		foreach( $currencies_to_show as $currency_id => $array )
		{
			$dustiest = $wallets->get_dustiest_wallet( $currency_id );
			if ( ! $dustiest )
				continue;
			$currency = $currencies->get( $currency_id );
			$currencies_to_show[ $currency_id ] = [
				'address' => $dustiest->get_address(),
				'currency_id' => $currency_id,
				'currency_name' => $currency->get_name(),
				'icon' => sprintf( '%s/src/static/images/currencies/%s.svg',
					$this->paths( 'url' ),
					$currency_id
				),
			];
			if ( isset( $currency->qr_code ) )
				$currencies_to_show[ $currency_id ][ 'qr_code_text' ] = $currency->qr_code;
		}

		// Handle the primary currency.
		if ( ! isset( $currencies_to_show[ $atts[ 'primary_currency' ] ] ) )
		{
			$random_key = array_rand( $currencies_to_show );
			$atts[ 'primary_currency' ] = $random_key;
		}
		$data->set( 'primary_currency', $atts[ 'primary_currency' ] );

		$data->set( 'currencies', $currencies_to_show );

		// Copy over these settings as they are.
		foreach( [
			'alignment',
			'show_currencies_as_icons',
			'show_currencies_as_select',
			'show_currency_as_text',
			'show_address',
		] as $key )
			$data->set( $key, $atts[ $key ] );

		// Handle the QR code.
		if ( $atts[ 'qr_code_enabled' ] )
		{
			$this->qr_code_enqueue_js();
			$data->set( 'qr_code_enabled', true );
			$qr_code_max_width = intval( $atts[ 'qr_code_max_width' ] );
			$qr_code_max_width = max( $qr_code_max_width, 100 );
			$data->set( 'qr_code_max_width', $qr_code_max_width );
		}
		else
			$data->set( 'qr_code_enabled', false );

		// Retrieve the html template.
		$html = $this->get_static_file( 'donations_html' );

		$data = json_encode( $data->to_array() );
		$data = base64_encode( $data );

		$html = str_replace( '##DATA##', $data, $html );

		$this->enqueue_js();
		$this->enqueue_css();

		return $html;


	}

}
