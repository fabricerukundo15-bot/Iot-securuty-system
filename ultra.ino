#include <WiFi.h>
#include <HTTPClient.h>

#define trig 18
#define echo 19
#define led 21
#define buzzer 22

const char* ssid = "WiFi_NAME";
const char* password = "WiFi_PASSWORD";
const char* server = "MY_PHP_BACKEND_FILE_LOCATION";

float distance;
long duration;
String eventStatus;

void setup(){
  Serial.begin(115200);
  pinMode(trig, OUTPUT);
  pinMode(echo, INPUT);
  pinMode(led, OUTPUT);
  pinMode(buzzer, OUTPUT);

  WiFi.begin(ssid, password);
  while(WiFi.status() != WL_CONNECTED){
    Serial.println("Connecting...");
    delay(500);
  }
  Serial.println("Connected");
  Serial.print("ESP32 IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  digitalWrite(trig, LOW);
  delayMicroseconds(2);
  digitalWrite(trig, HIGH);
  delayMicroseconds(10);
  digitalWrite(trig, LOW);

  duration = pulseIn(echo, HIGH);
  distance = (duration * 0.034) / 2;

  Serial.print("Distance: ");
  Serial.print(distance);
  Serial.println(" cm");

  if (distance <= 400) {
    digitalWrite(led, HIGH);
    digitalWrite(buzzer, HIGH);
    eventStatus = "Object detected near gate!";                
  }
  else {
    digitalWrite(led, LOW);
    digitalWrite(buzzer, LOW);
    eventStatus = "Everything is okay on the gate!";  
  }
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    http.begin(server);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String data = "distance=" + String(distance) +
                  "&eventStatus=" + String(eventStatus);
    int response = http.POST(data);

    Serial.print("HTTP response code: ");
    Serial.println(response);
    http.end();
  }
  delay(200);
}