<?php

namespace classes;
use Dompdf\Dompdf;

class Invoice {
    protected string $html;
    protected string $lines = "";
    protected string $invoiceID;
    protected string $path;
    protected string $url;

    public function generateInvoice(): void {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $path = wp_upload_dir()["basedir"].$this->path;
        $url = wp_upload_dir()["baseurl"].$this->path;
        file_put_contents($path."/RentingAgreement_".$this->invoiceID.".pdf", $output);
        $this->url = $url."/RentingAgreement_".$this->invoiceID.".pdf";
    }

    public function insertNewLine(string $count, string $name, string $price): void {
        $this->lines .= "<tr>";
        $this->lines .= "<td>".$count."</td>";
        $this->lines .= "<td>".$name."</td>";
        $this->lines .= "<td class='unit-price'>€ ".$price."</td>";
        $this->lines .= "<td>€ ".number_format(intval($price)*$count,2,".",",")."</td>";
        $this->lines .= "</tr>";
    }

    public function getURL(): string {
        return $this->url;
    }

    public function getInvoiceID(): string {
        return $this->invoiceID;
    }

    public function getInvoiceURL(): string {
        return WP_CONTENT_DIR."/uploads/kiosk-faktura/RentingAgreement_".$this->invoiceID.".pdf";
    }

    public function send(string $from, string $subject, string $template, string $to): bool{

        apply_filters("wp_mail_from", $from);
        add_filter('wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });

        $headers = "From: ".$from."  \r\n
                Reply-to: ".$from."  \r\n";

        //wp_mail("booking@elmirador.dk", $subject, $message, $headers, array(ABSPATH."wp-content/uploads".explode("/uploads", get_post_meta($post_id, "invoice", true))[1]));
        return wp_mail($to, $subject, $template, $headers, array(WP_CONTENT_DIR."/uploads/kiosk-faktura/RentingAgreement_".$this->invoiceID.".pdf"));
    }

    public function replaceShortcode(string $shortcode, mixed $replacement): void {
        $this->html = str_replace($shortcode, $replacement, $this->html);
    }
}