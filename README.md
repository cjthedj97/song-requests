This is my new song request site built to connect to any SMTP server using PHPMailer.
PHPMailer is not inclued in this repo to download and place where the code expectes you can run the following



```bash
latest_release=$(curl -s https://api.github.com/repos/PHPMailer/PHPMailer/releases/latest | jq -r .tag_name) && wget https://github.com/PHPMailer/PHPMailer/archive/refs/tags/${latest_release}.zip && unzip ${latest_release}.zip -d phpmailer/ && cp -R phpmailer/PHPMailer-*/* phpmailer/ && rm -R phpmailer/PHPMailer-* ${latest_release}.zip
```
