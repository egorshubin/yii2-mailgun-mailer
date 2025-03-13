# Mailgun mailer component for Yii2 framework

This is a fork for mailgun 3

## Usage
Configure `YarCode\Yii2\Mailgun\Mailer` as your mailer.
```
  'mailer' => [
      'class' => \YarCode\Yii2\Mailgun\Mailer::class,
      'domain' => 'example.org',
      'apiKey' => 'CHANGE-ME',
  ],
```
Now you can send your emails as usual.
```
$message = \Yii::$app->mailer->compose()
  ->setSubject('test subject')
  ->setFrom('test@example.org')
  ->setHtmlBody('test body')
  ->setTo('user@example.org');

\Yii::$app->mailer->send($message);
```
## Licence ##

MIT
    
## Links to original repo (outdated, Mailgun 2!) ##

* [GitHub repository](https://github.com/yarcode/yii2-mailgun-mailer)
* [Composer package](https://packagist.org/packages/yarcode/yii2-mailgun-mailer)
