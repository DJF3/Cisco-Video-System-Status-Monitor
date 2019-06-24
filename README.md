# Cisco-Video-System-Status-Monitor

Display the status of a Cisco video system on a tablet/computer/screen (outside the meeting room). 


[Requirements](#requirements)

[Features](#features)

[Setup](#setup)

[How does it work?](#how)

[FYI](#fyi)

[Roadmap](#roadmap)

[Feedback & Support](#feedback)


# What does it do?

Overview

![Overview](https://user-images.githubusercontent.com/4991841/60021749-d259c180-9692-11e9-900c-14eb8feab7e9.png)





<a name="requirements"/>


# Requirements

* PHP  (tested with PHP 7.2, but should work with 5.x)
* Video system firmware CE 9.6.1 or higher
* Features supported per device: see table:

| Feature  | Supported Devices |
| -------------: | ------------- |
| Call detect  | All devices  |
| People count  | SX80 RoomKit/Mini CodecPlus CodecPro Room55 Room70/Room55D Room70G2 |
| People presence  | SX80 RoomKit/Mini CodecPlus CodecPro Room55 Room70/Room55D Room70G2 _plus_ SX20 MX200G2/MX300G2 MX700/MX800/MX800D |




<a name="features"/>

# Features
The following features are supported:

### DOES:
* Display status of video system (in-call or idle)
* Status text is customizable 
* Shows per status a color (red or green)
* Displays Meeting room name
* Displays People count (if supported)
* Displays People presence (if supported + people count is not supported)

### DOESN'T:
* Clean your dishes



<a name="setup"/>

# Setup

Follow these steps to setup the Video System monitoring code. 


<img width="883" alt="image" src="https://user-images.githubusercontent.com/4991841/60021775-df76b080-9692-11e9-8b81-093db3fbac7f.png">


<img width="884" alt="image" src="https://user-images.githubusercontent.com/4991841/60021805-ec939f80-9692-11e9-9d2b-f978bd355059.png">


<img width="884" alt="image" src="https://user-images.githubusercontent.com/4991841/60021821-f61d0780-9692-11e9-894f-5e5cefb20b4b.png">


<img width="885" alt="image" src="https://user-images.githubusercontent.com/4991841/60021844-00d79c80-9693-11e9-993e-bbc6c2034d4a.png">

<br>
<br>
<br>
<br>
<br>
<br>
<a name="how"/>

# How does it work?
What happens when you deploy this code? 


<br>

<img width="886" alt="image" src="https://user-images.githubusercontent.com/4991841/60021866-10ef7c00-9693-11e9-835a-8d43cd4462fb.png">

<br>

<img width="883" alt="image" src="https://user-images.githubusercontent.com/4991841/60021885-18af2080-9693-11e9-8177-ec8e7682119a.png">

<br>

<img width="884" alt="image" src="https://user-images.githubusercontent.com/4991841/60021898-219ff200-9693-11e9-9e8c-6fc48f7c50c3.png">

<br>

<img width="883" alt="image" src="https://user-images.githubusercontent.com/4991841/60021922-2a90c380-9693-11e9-90b4-a1021bd4faf5.png">

<br>

<img width="883" alt="image" src="https://user-images.githubusercontent.com/4991841/60021943-32e8fe80-9693-11e9-9171-213c242e483f.png">

<br>

<img width="884" alt="image" src="https://user-images.githubusercontent.com/4991841/60021969-4005ed80-9693-11e9-89c8-31e7e68a51dc.png">

<br>

<img width="882" alt="image" src="https://user-images.githubusercontent.com/4991841/60022000-498f5580-9693-11e9-8a6e-dbf352fd5103.png">

<br>

<img width="885" alt="image" src="https://user-images.githubusercontent.com/4991841/60022027-5318bd80-9693-11e9-9b64-213ff6e0845e.png">



<br>
<br>
<br>

<a name="fyi"/>

# FYI
* jquery.min.js is included in order to minimize dependencies. You can use the Google hosted jquery.min.js by changing the 'script src' link on line 20 to: ``` https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js ```


<br>
<br>
<br>

<a name="roadmap"/>

# Roadmap
* Make the background and text placement/formatting easy to customize
* Include jQuery to remove internet access dependency
* Dashboard that displays the status for multiple meetingrooms. (Remove people count and only use _people presence_? (do we care about _the number of people_?)


<a name="feedback"/>

# Feedback & Support
Will add this later. For now, open issues in this repository
