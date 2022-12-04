# Clever Cooks

## Getting started

1. Download MAMP `https://www.mamp.info/en/downloads/`
1. Start MAMP and launch webstart. You should see
   browser tab launched with `http://localhost:8888/MAMP/?language=English`
1. In browser tab above, click on link `phpinfo`. To get the root 
   directory, find the text `DOCUMENT_ROOT`. The value should be like `	/Applications/MAMP/htdocs`
1. Open terminal. Change directory to    
   `cd <dir/to/your/MAMP/htdocs>`
1. Check out this repository   
    `git clone https://gitlab.com/clever-coders/clever-cooks.git`
1. Confirm `clever-cooks` directory is created in 
   `<dir/to/your/MAMP/htdocs/clever-cooks>`
1. Confirm setup is loading. In a browser tab, go to 
   `http://localhost:8888/clever-cooks/test/test.php`

## Setting up the DB
1. 

## Creating your own branch and committing changes
1. Make sure you are in the directory `<dir/to/your/MAMP/htdocs/clever-cooks>`
1. Create a branch before starting any change
   `git checkout -b <replace with your branch name>`
1. `git commit . -m "<your commit message>"`
1. Commit changes as you go
