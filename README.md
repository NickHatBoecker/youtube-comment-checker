# Youtube Comment Checker

Are you collaborating with other YouTubers? Did they upload a video and you constantly check if there are new comments? Well this little tool will help you out.

Paste some YouTube URLs and see on the blink of an eye if there are new comments.

![](https://github.com/NickHatBoecker/youtube-comment-checker/blob/master/screenshot.jpg?raw=true)

## Development 

You will need an access token for the YouTube API.

```bash
yarn dev:all // start symfony server and encore-assets
```

## Deployment

1. For the PHP part fetch git repository changes on server
2. For the vue part build files with `yarn build` locally and copy them to {serverPath}/public
