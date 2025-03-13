<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */
namespace YarCode\Yii2\Mailgun;

use Mailgun\Mailgun;
use yii\base\InvalidConfigException;
use yii\mail\BaseMailer;
use Mailgun\HttpClient\HttpClientConfigurator;

class Mailer extends BaseMailer
{
    /** @var string */
    public $messageClass = Message::class;
    /** @var string */
    protected $apiKey;
    /** @var string */
    protected $domain;
    /** @var Mailgun */
    protected $client;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!isset($this->apiKey, $this->domain)) {
            throw new InvalidConfigException('Invalid mailer configuration');
        }
        
        // Correct way to configure Mailgun
        $configurator = new HttpClientConfigurator();
        $configurator->setApiKey($this->apiKey);

        $this->client = new Mailgun($configurator);
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

/**
 * @param Message $message
 */
protected function sendMessage($message)
{
    // 1. Build up the Mailgun params array from your Message object or however
    //    your application defines these fields. The keys (from, to, subject, text, html)
    //    correspond to Mailgun’s v3 API.
    $params = [
        'from'    => $message->getFrom(),     // e.g. 'noreply@yourdomain.com'
        'to'      => $message->getTo(),       // e.g. 'john.doe@example.com'
        'subject' => $message->getSubject(),  // e.g. 'Test Subject'
        // If your “message builder” provides either plain-text or HTML content,
        // you can set one or both. Mailgun will send multi-part automatically if both exist.
        'text'    => $message->getTextBody(), // e.g. "Hello in plain text!"
        'html'    => $message->getHtmlBody()  // e.g. "<h1>Hello in HTML!</h1>"
    ];

    // 2. If your Message object has attachments, add them. By default,
    //    ‘attachment’ is for standard attachments. If you need inline images, 
    //    you would put them under ‘inline’ => [...]
    $files = $message->getMessageBuilder()->getFiles();  // however you fetch them
    if (!empty($files)) {
        $params['attachment'] = $files;
    }

    // 3. Invoke Mailgun v3’s send() method. Note that $this->client 
    //    must be an instance of Mailgun\Mailgun, and $this->domain 
    //    is your verified sending domain.
    $response = $this->client->messages()->send($this->domain, $params);

    // 4. Return or log the response
    return $response->getMessage();
}

}
