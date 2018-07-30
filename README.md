# Pegasus Blog
Ever need a way to show blogs posts in your theme? this will allow you to have a customizable grid/list view for your blogs posts with pagination and great category implementation with just a simple shortcode.

## Usage
How to use in your WordPress site
`[blog]`


## Installation
How to install to your WordPress site

### Simple Instructions 
Go to [Github](https://github.com/Visionquest-Development/pegasus-blog "Github") and click "Clone or download" and make sure it says "Clone with HTTPS" at the top and click "Download Zip" and save it to a folder on your PC. Then, go to WordPress Back-end and go to Plugins -> Add New and then click on "Upload Plugin" and upload the zip file you downloaded from Github.

### Advanced Setup 
1.) Upload via FTP/WordPress Admin<br>
Download zip file from [here](https://github.com/Visionquest-Development/pegasus-blog/archive/master.zip "Github") and on extraction make sure the folder name you extract to is saved as pegasus-blog instead of pegasus-blog-master
Then upload to you server using an FTP program or Use the WordPress Plugin Upload feature in the WordPress Admin.
2.) In the terminal:
#### For https connection use:
`git clone https://github.com/Visionquest-Development/pegasus-blog.git pegasus-blog`

#### For SSH implementation use this command and make sure you have the SSH key setup on both your terminal and github account. You will need to use your passkey for each update.
`git clone git@github.com:Visionquest-Development/pegasus-blog.git pegasus-blog`



## How to update
### Simple Instructions
Use the WordPress Admin or your favorite FTP program to delete the old plugin from the plugins folder. Then follow the Installation instructions again from above.

### Advanced Setup 
If you used the Advanced Setup, all you need to do is `cd /path/where/plugin/exists` && `git pull`.


## How to contribute
If you plan on helping make this plugin better then please follow our guidelines for contributions. We recommend you use a terminal and git clone our repository. Then, use `git checkout -b new-branch-name` to create your own branch, and rename it to whatever you want by replacing `new-branch-name`. Make your changes and then add and commit them to your branch. Make sure to use `git push -u origin new-branch-name` with the new name when you get done committing your changes to set the upstream for your branch. Then, submit a Pull Request for submission. We will review Pull Requests and accept them if it fits with our current setup.



