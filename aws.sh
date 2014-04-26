git add *
read -p "Default commit message? y/n " default

if [ "$default" == "Y" ] || [ "$default" == "y" ]; then
	message = "default"
else
	read -p "Please input your commit message: " message
fi

git commit -m $message

git push 
git push openshift master
