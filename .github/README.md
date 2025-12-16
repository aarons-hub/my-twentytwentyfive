# GitHub Actions Configuration

This directory contains GitHub Actions workflows for automating various processes in the WordPress theme repository.

## Workflows

### Deploy to Production (`workflows/deploy-to-production.yml`)

Automatically deploys WordPress theme files from this staging repository to a production repository.

**Triggers:**
- Automatically on push to `main` branch
- Manually via workflow_dispatch (Actions tab)

**Features:**
- ✅ Secure authentication using GitHub PAT
- ✅ Automatic file synchronization with rsync
- ✅ Smart default branch detection
- ✅ Error handling and deployment summaries
- ✅ Configurable production repository
- ✅ Excludes `.git` and `.github` directories

**Setup Required:**
See [DEPLOYMENT.md](DEPLOYMENT.md) for complete setup instructions.

**Quick Setup:**
1. Create a production repository (e.g., `aarons-hub/my-twentytwentyfive-production`)
2. Generate a GitHub Personal Access Token with `repo` scope
3. Add the token as `PRODUCTION_REPO_TOKEN` secret in repository settings
4. Push to main branch or manually trigger the workflow

## Documentation

- [DEPLOYMENT.md](DEPLOYMENT.md) - Complete deployment setup guide
  - Step-by-step configuration instructions
  - Troubleshooting common issues
  - Security best practices
  - Advanced configuration options

## Security

All workflows follow security best practices:
- Minimal permissions (read-only by default)
- Secure credential handling
- No token exposure in process lists
- Regular security scanning with CodeQL

## Support

For issues or questions:
1. Review the workflow logs in the Actions tab
2. Check [DEPLOYMENT.md](DEPLOYMENT.md) for troubleshooting
3. Verify all required secrets and variables are configured
