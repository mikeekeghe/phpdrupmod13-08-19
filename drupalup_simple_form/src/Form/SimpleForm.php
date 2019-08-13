<?php

namespace Drupal\drupalup_simple_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Our simple form class.
 */
class SimpleForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'drupalup_simple_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['number_1'] = [
            '#type' => 'textfield',
            '#title' => $this->t('VIN number'),
        ];


        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Fetch Details'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $myUrl = "https://www.autorescue.ng/dealer/index.php";
        $email = 'info@mymech.ng';
        $pass = 'mech1000';
        $vin = $form_state->getValue('number_1');
        $eol = "\r\n";
        $data = '';
        $mime_boundary=md5(time());
        $data .= '--' . $mime_boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="email"' . $eol . $eol;
        $data .= $email . $eol;
        $data .= '--' . $mime_boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="password"' . $eol . $eol;
        $data .= $pass . $eol;
        $data .= '--' . $mime_boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="vin"' . $eol . $eol;
        $data .= $vin . $eol;
        $params = array('http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: multipart/form-data; boundary=' . $mime_boundary,
            'content' => $data
        ));
        $ctx = stream_context_create($params);
        $response = file_get_contents($myUrl, FILE_TEXT, $ctx);
        drupal_set_message($response);

    }

}
