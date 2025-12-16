# Deployment Setup Guide

This document provides instructions for setting up the Continuous Deployment (CD) pipeline that automatically deploys WordPress theme files from this staging repository to a production repository.

## Overview

The CD pipeline is configured in `.github/workflows/deploy-to-production.yml` and automatically triggers when code is pushed to the `main` branch. It can also be manually triggered via the GitHub Actions interface.

## Setup Instructions

### 1. Create Production Repository

First, ensure you have a production repository where the theme files will be deployed. By default, the workflow expects:
- **Default Production Repo**: `aarons-hub/my-twentytwentyfive-production`

You can customize this by:
- Setting a repository variable named `PRODUCTION_REPO` with the value `owner/repo`
- Or manually specifying it when triggering the workflow

### 2. Create GitHub Personal Access Token (PAT)

The workflow requires a Personal Access Token to authenticate with the production repository.

1. Go to GitHub Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Click "Generate new token (classic)"
3. Give it a descriptive name (e.g., "WordPress Theme Production Deployment")
4. Set expiration as needed
5. Select the following scope:
   - ✅ **repo** (Full control of private repositories)
6. Click "Generate token"
7. **Important**: Copy the token immediately - you won't be able to see it again!

### 3. Add Secret to Repository

1. Go to your staging repository: `aarons-hub/my-twentytwentyfive`
2. Navigate to Settings → Secrets and variables → Actions
3. Click "New repository secret"
4. Name: `PRODUCTION_REPO_TOKEN`
5. Value: Paste the Personal Access Token from step 2
6. Click "Add secret"

### 4. (Optional) Configure Production Repository Variable

If you want to use a different production repository than the default:

1. Go to Settings → Secrets and variables → Actions → Variables tab
2. Click "New repository variable"
3. Name: `PRODUCTION_REPO`
4. Value: `owner/repository-name` (e.g., `aarons-hub/my-custom-production-repo`)
5. Click "Add variable"

## How It Works

### Automatic Deployment

When you push commits to the `main` branch:

1. The workflow automatically triggers
2. Checks out the staging repository
3. Clones the production repository using the PAT
4. Copies all WordPress theme files (excluding `.git` and `.github` directories)
5. Commits the changes with a message like: "Deploy from staging - [commit SHA]: [commit message]"
6. Pushes to the production repository's `main` branch
7. Provides a deployment summary in the workflow logs

### Manual Deployment

You can also trigger deployments manually:

1. Go to Actions → Deploy to Production
2. Click "Run workflow"
3. Optionally specify a different production repository
4. Click "Run workflow"

## What Gets Deployed

The workflow copies all theme files including:
- WordPress theme files (`style.css`, `theme.json`, `functions.php`, etc.)
- Assets directory (`assets/`, `css/`, `js/`, `images/`)
- Template files (`templates/`, `parts/`, `patterns/`)
- Any other theme-related files

**Excluded files:**
- `.git` directory (version control)
- `.github` directory (workflow files)

## Troubleshooting

### Error: "PRODUCTION_REPO_TOKEN secret is not set"

**Solution**: Follow step 3 above to add the `PRODUCTION_REPO_TOKEN` secret to your repository.

### Error: "Failed to clone production repository"

**Possible causes:**
1. The production repository doesn't exist - create it first
2. The PAT doesn't have access - ensure the token has `repo` scope
3. The PAT has expired - generate a new token and update the secret
4. The repository name is incorrect - verify the `PRODUCTION_REPO` variable or default value

### No changes to deploy

This is normal if no theme files have changed since the last deployment. The workflow will exit successfully without making a commit.

## Monitoring Deployments

### Viewing Deployment Status

1. Go to the Actions tab in your repository
2. Click on "Deploy to Production"
3. View the list of workflow runs
4. Click on any run to see detailed logs and status

### Deployment Summary

After each successful deployment, the workflow provides a summary showing:
- Staging commit SHA
- Production repository
- Deployment timestamp

## Security Notes

- **Never commit the Personal Access Token to the repository**
- The token is stored securely as a GitHub secret
- The token is only accessible during workflow execution
- Consider using a dedicated service account for deployments
- Regularly rotate your PATs for security

## Advanced Configuration

### Customizing the Deployment Process

You can modify `.github/workflows/deploy-to-production.yml` to:
- Add pre-deployment checks
- Include build steps (if needed)
- Add post-deployment notifications (Slack, email, etc.)
- Deploy to multiple environments
- Add approval gates for production deployments

### Example: Adding Slack Notifications

Add this step after the deployment:

```yaml
- name: Notify Slack
  if: always()
  uses: slackapi/slack-github-action@v1
  with:
    webhook-url: ${{ secrets.SLACK_WEBHOOK_URL }}
    payload: |
      {
        "text": "Deployment ${{ job.status }}: ${{ steps.staging-commit.outputs.sha }}"
      }
```

## Support

If you encounter issues not covered in this guide:
1. Check the workflow logs in the Actions tab
2. Verify all secrets and variables are configured correctly
3. Ensure the production repository exists and is accessible
4. Review the error messages for specific troubleshooting steps
