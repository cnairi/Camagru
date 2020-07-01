# CAMAGRU | SHARE - LIKE - COMMENT
Camagru is an Instagram-like platform made to share pictures from your webcam or uploaded from your computer, mixed with fun filters. It also gives an opportunity to share, comment and like with the Camagru Community!

It's the first web project of 42 School, and we were only authorized to use CSS Framework. I've chosen Bulma for this one!

The DB is mySql, and the project is realized in HTML/CSS, with a little Javascript. Feel free to contact me if you have any question.

# FEATURES
![Homepage](../master/Previews/Homepage.png)
<p align="center">Homepage, with no photos shared yet.</p>

![Signin](../master/Previews/SignIn_page.png)
<p align="center">Sign-in page.</p>

![Signup](../master/Previews/SignUp_Page.png)
<p align="center">Sign-up page.</p>

![Camera](../master/Previews/PictureEditingPage.png)
<p align="center">The most important part of the project. Once logged in, the user is able to take pictures from his/her camera and to edit them, adding fun filters. He or she also can upload a pic from his/her computer before sharing it! Finally, if you're not satisfied from the result, you could delete it by clicking on your pic.</p>

![Social](../master/Previews/SociaWall.png)
<p align="center">Here we are, your master pieces are out there, in the wild world of Camagru! Once your pictures are on the social wall, anyone can see them but only other Camagru users can like or comment them.</p>

![Interactions](../master/Previews/Interactions_page.png)
<p align="center">Let's spread some love by commenting other users' photos or liking them! Be sure you will get some in exchange in our strong and caring community.</p>

![trending](../master/Previews/HomePagePics.png)
<p align="center">Get a chance to be featured among the trending pics of the community and to appear on our homepage.</p>

![profile](../master/Previews/ProfilePage.png)
<p align="center">More practically, you can modify your info at any time and also recover your password if you're not logged in and can't remember it!</p>

Clone this project in your htdocs root folder.
Change credentials in config/database.php. You have to put your own phpmyadmin credentials in order to make it work.
In php.ini, you may need to change your smtp_port to your local ip to be able to sen emails. Be careful, it works fine with gmail addresses, but icloud does not seem to accept camagru emails.
