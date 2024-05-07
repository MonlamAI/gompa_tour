async function query(data) {
	const response = await fetch(
		"https://bp8a31wa86krz437.us-east-1.aws.endpoints.huggingface.cloud",
		{
			headers: { 
				"Accept" : "application/json",
				"Authorization": "Bearer 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",
				"Content-Type": "application/json" 
			},
			method: "POST",
			body: JSON.stringify(data),
		}
	);
	const result = await response.json();
	return result;
}

query({
    "inputs": "<2bo>The answer to the universe is",
    "parameters": {}
}).then((response) => {
	console.log(JSON.stringify(response));
});