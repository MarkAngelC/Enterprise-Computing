pipeline {
    agent any
    environment{
        NETLIFY_SITE_ID = '68be4546-1f16-4c1d-a58e-18afc8935b82'
        NETLIFY_AUTH_TOKEN = credentials('tempToken')
    }
    stages {
        stage('Build') {
            agent {
                docker {
                    image 'node:24.14.0-alpine'
                    reuseNode true
                }
            }
            steps {
                sh '''
                    # list all files
                    ls -la
                    node --version
                    npm --version
                    npm install
                    # npm ci
                    npm run build
                    ls -la
                '''
            }
        }

        stage('Test') {
            agent {
                docker {
                    image 'node:24.14.0-alpine'
                    reuseNode true
                }
            }
            steps {
                sh '''
                    test -f build/index.html
                    npm test
                '''
            }
        }

        stage('Deploy') {
            agent {
                docker {
                    image 'node:24.14.0-alpine'
                    reuseNode true
                }
            }
            steps {
                sh '''
                    npm install netlify-cli
                    node_modules/.bin/netlify --version
                    echo "Deploying to production. Site ID: $NETLIFY_SITE_ID"
                    node_modules/.bin/netlify status
                    # deploy to build folder
                    node_modules/.bin/netlify deploy --prod --dir=build
                '''
            }
        }
    }
}