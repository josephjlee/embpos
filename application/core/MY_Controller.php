<?php 

class MY_Controller extends CI_Controller {

  protected $use_view = '';
  protected $use_layout = '';
  protected $external_scripts = [];
  protected $stylesheets = [];

  protected function render($data = [])
  {
    $dir = $this->router->directory;

    $view = empty($this->use_view) ? $dir . $this->router->class. '/' . $this->router->method : $this->use_view;

    // Make sure any scripts/stylesheets are avalaible to the view
    $data['external_scripts'] = $this->external_scripts;
    $data['stylesheets'] = $this->stylesheets;

    // Build our notices from the theme's view file
    $data['notice'] = $this->load->view('theme/notice', ['notice' => $this->message()], TRUE);

    // We'll make the view content available to the template
    $data['view_content'] = $this->load->view($view, $data, TRUE);

    // Render layout
    $layout = empty($this->use_layout) ? 'index' : $this->use_layout;

    $this->load->view('theme/'.$layout, $data);

    // Reset our custom view attributes.
    $this->use_view = $this->use_layout = '';
  }

  public function view($view) 
  {
    $this->use_view = $view;

    return $this;
  }

  public function layout($layout)
  {
    $this->use_layout = $layout;

    return $this;
  }

  public function add_script($filename)
  {
    if (strpos($filename, 'http') == FALSE)
    {
      $filename = base_url('assets/js/') . $filename;
    }

    $this->external_scripts[] = $filename;

    return $this;
  }
  
  public function add_style($filename)
  {
    if (strpos($filename, 'http') == FALSE)
    {
      $filename = base_url('assets/css/') . $filename;
    }

    $this->stylesheets[] = $filename;

    return $this;
  }
  
}