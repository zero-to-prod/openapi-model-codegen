docker_image="gitlab-registry.sweetwater.com/web/teams/production-support/digital-engineering-hub/openapi-model-codegen:main"

docker pull $docker_image

echo "What is the API endpoint for the swagger doc?"
read API_ENDPOINT

echo "Where would you like the output files to go? (default ./generated)"
read OUTPUT
if [[ -z $OUTPUT ]]; then
	OUTPUT="./generated"
fi

echo "What's the namespace e.g. App\Models"
read -r MODELS_DIR

docker run -v $(pwd)/$OUTPUT:/output -it $docker_image $API_ENDPOINT /output $MODELS_DIR
