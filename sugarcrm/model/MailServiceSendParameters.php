<?php

class MailServiceSendParameters
{
    public $html_body;
    public $text_body;
    public $subject;
    public $from_email;
    public $from_name;
    public $x_headers;
    public $merge_field_delimeters;
    public $global_merge_data;
    public $recipient_merge_vars;
    public $recipients;
    public $images;
    public $attachments;

    public static function fromArray(array $params)
    {
        $sendParameters = new MailServiceSendParameters();

        $sendParams->html_body   = $params['html_body'];
        $sendParams->text_body   = $params['text_body'];
        $sendParams->subject     = $params['subject'];
        $sendParams->from_email  = $params['from_email'];
        $sendParams->from_name   = $params['from_name'];
        $sendParams->x_headers   = $params['x_headers'];
        $sendParams->merge_field_delimeters = $params['merge_field_delimeters'];
        $sendParams->global_merge_data      = $params['global_merge_data'];
        $sendParams->recipient_merge_vars   = $params['recipient_merge_vars'];
        $sendParams->recipients             = $params['recipients'];
        $sendParams->images      = $params['images'];
        $sendParams->attachments = $params['attachments'];

        return $sendParams;
    }

    public function toArray()
    {
        return array(
            'html_body' => $this->html_body,
            'text_body' => $this->text_body,
            'subject' => $this->subject,
            'from_email' => $this->from_email,
            'from_name' => $this->from_name,
            'x_headers' => $this->x_headers,
            'merge_field_delimeters' => $this->merge_field_delimeters,
            'global_merge_data' => $this->global_merge_data,
            'recipient_merge_vars' => $this->recipient_merge_vars,
            '$recipients' => $this->recipients,
            'images' => $this->images,
            '$attachments' => $this->attachments,
        );
    }
}
