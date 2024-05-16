<?php
// This file was auto-generated from sdk-root/src/data/devicefarm/2015-06-23/examples-1.json
return [ 'version' => '1.0', 'examples' => [ 'CreateDevicePool' => [ [ 'input' => [ 'name' => 'MyDevicePool', 'description' => 'My Android devices', 'projectArn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', 'rules' => [], ], 'output' => [ 'devicePool' => [], ], 'comments' => [ 'input' => [ 'name' => 'A device pool contains related devices, such as devices that run only on Android or that run only on iOS.', 'projectArn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example creates a new device pool named MyDevicePool inside an existing project.', 'id' => 'createdevicepool-example-1470862210860', 'title' => 'To create a new device pool', ], ], 'CreateProject' => [ [ 'input' => [ 'name' => 'MyProject', ], 'output' => [ 'project' => [ 'name' => 'MyProject', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:5e01a8c7-c861-4c0a-b1d5-12345EXAMPLE', 'created' => '1472660939.152', ], ], 'comments' => [ 'input' => [ 'name' => 'A project in Device Farm is a workspace that contains test runs. A run is a test of a single app against one or more devices.', ], 'output' => [], ], 'description' => 'The following example creates a new project named MyProject.', 'id' => 'createproject-example-1470862210860', 'title' => 'To create a new project', ], ], 'CreateRemoteAccessSession' => [ [ 'input' => [ 'name' => 'MySession', 'configuration' => [ 'billingMethod' => 'METERED', ], 'deviceArn' => 'arn:aws:devicefarm:us-west-2::device:123EXAMPLE', 'projectArn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'output' => [ 'remoteAccessSession' => [], ], 'comments' => [ 'input' => [ 'deviceArn' => 'You can get the device ARN by using the list-devices CLI command.', 'projectArn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example creates a remote access session named MySession.', 'id' => 'to-create-a-remote-access-session-1470970668274', 'title' => 'To create a remote access session', ], ], 'CreateUpload' => [ [ 'input' => [ 'name' => 'MyAppiumPythonUpload', 'type' => 'APPIUM_PYTHON_TEST_PACKAGE', 'projectArn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'output' => [ 'upload' => [ 'name' => 'MyAppiumPythonUpload', 'type' => 'APPIUM_PYTHON_TEST_PACKAGE', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:upload:5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/b5340a65-3da7-4da6-a26e-12345EXAMPLE', 'created' => '1472661404.186', 'status' => 'INITIALIZED', 'url' => 'https://prod-us-west-2-uploads.s3-us-west-2.amazonaws.com/arn%3Aaws%3Adevicefarm%3Aus-west-2%3A123456789101%3Aproject%3A5e01a8c7-c861-4c0a-b1d5-12345EXAMPLE/uploads/arn%3Aaws%3Adevicefarm%3Aus-west-2%3A123456789101%3Aupload%3A5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/b5340a65-3da7-4da6-a26e-12345EXAMPLE/MyAppiumPythonUpload?AWSAccessKeyId=1234567891011EXAMPLE&Expires=1472747804&Signature=1234567891011EXAMPLE', ], ], 'comments' => [ 'input' => [ 'projectArn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example creates a new Appium Python test package upload inside an existing project.', 'id' => 'createupload-example-1470864711775', 'title' => 'To create a new test package upload', ], ], 'DeleteDevicePool' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2::devicepool:123-456-EXAMPLE-GUID', ], 'output' => [], 'comments' => [ 'input' => [ 'arn' => 'You can get the device pool ARN by using the list-device-pools CLI command.', ], 'output' => [], ], 'description' => 'The following example deletes a specific device pool.', 'id' => 'deletedevicepool-example-1470866975494', 'title' => 'To delete a device pool', ], ], 'DeleteProject' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'output' => [], 'comments' => [ 'input' => [ 'arn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example deletes a specific project.', 'id' => 'deleteproject-example-1470867374212', 'title' => 'To delete a project', ], ], 'DeleteRemoteAccessSession' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:session:EXAMPLE-GUID-123-456', ], 'output' => [], 'comments' => [ 'input' => [ 'arn' => 'You can get the remote access session ARN by using the list-remote-access-sessions CLI command.', ], 'output' => [], ], 'description' => 'The following example deletes a specific remote access session.', 'id' => 'to-delete-a-specific-remote-access-session-1470971431677', 'title' => 'To delete a specific remote access session', ], ], 'DeleteRun' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:EXAMPLE-GUID-123-456', ], 'output' => [], 'comments' => [ 'input' => [ 'arn' => 'You can get the run ARN by using the list-runs CLI command.', ], 'output' => [], ], 'description' => 'The following example deletes a specific test run.', 'id' => 'deleterun-example-1470867905129', 'title' => 'To delete a run', ], ], 'DeleteUpload' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:upload:EXAMPLE-GUID-123-456', ], 'output' => [], 'comments' => [ 'input' => [ 'arn' => 'You can get the upload ARN by using the list-uploads CLI command.', ], 'output' => [], ], 'description' => 'The following example deletes a specific upload.', 'id' => 'deleteupload-example-1470868363942', 'title' => 'To delete a specific upload', ], ], 'GetAccountSettings' => [ [ 'input' => [], 'output' => [ 'accountSettings' => [ 'awsAccountNumber' => '123456789101', 'unmeteredDevices' => [ 'ANDROID' => 1, 'IOS' => 2, ], ], ], 'comments' => [ 'input' => [], 'output' => [], ], 'description' => 'The following example returns information about your Device Farm account settings.', 'id' => 'to-get-information-about-account-settings-1472567568189', 'title' => 'To get information about account settings', ], ], 'GetDevice' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2::device:123EXAMPLE', ], 'output' => [ 'device' => [ 'name' => 'LG G2 (Sprint)', 'arn' => 'arn:aws:devicefarm:us-west-2::device:A0E6E6E1059E45918208DF75B2B7EF6C', 'cpu' => [ 'architecture' => 'armeabi-v7a', 'clock' => 2265.6, 'frequency' => 'MHz', ], 'formFactor' => 'PHONE', 'heapSize' => 256000000, 'image' => '75B2B7EF6C12345EXAMPLE', 'manufacturer' => 'LG', 'memory' => 16000000000, 'model' => 'G2 (Sprint)', 'os' => '4.2.2', 'platform' => 'ANDROID', 'resolution' => [ 'height' => 1920, 'width' => 1080, ], ], ], 'comments' => [ 'input' => [], 'output' => [], ], 'description' => 'The following example returns information about a specific device.', 'id' => 'getdevice-example-1470870602173', 'title' => 'To get information about a device', ], ], 'GetDevicePool' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'output' => [ 'devicePool' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can obtain the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example returns information about a specific device pool, given a project ARN.', 'id' => 'getdevicepool-example-1470870873136', 'title' => 'To get information about a device pool', ], ], 'GetDevicePoolCompatibility' => [ [ 'input' => [ 'appArn' => 'arn:aws:devicefarm:us-west-2::app:123-456-EXAMPLE-GUID', 'devicePoolArn' => 'arn:aws:devicefarm:us-west-2::devicepool:123-456-EXAMPLE-GUID', 'testType' => 'APPIUM_PYTHON', ], 'output' => [ 'compatibleDevices' => [], 'incompatibleDevices' => [], ], 'comments' => [ 'input' => [ 'devicePoolArn' => 'You can get the device pool ARN by using the list-device-pools CLI command.', ], 'output' => [], ], 'description' => 'The following example returns information about the compatibility of a specific device pool, given its ARN.', 'id' => 'getdevicepoolcompatibility-example-1470925003466', 'title' => 'To get information about the compatibility of a device pool', ], ], 'GetJob' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2::job:123-456-EXAMPLE-GUID', ], 'output' => [ 'job' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the job ARN by using the list-jobs CLI command.', ], 'output' => [], ], 'description' => 'The following example returns information about a specific job.', 'id' => 'getjob-example-1470928294268', 'title' => 'To get information about a job', ], ], 'GetOfferingStatus' => [ [ 'input' => [ 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE=', ], 'output' => [ 'current' => [ 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE' => [ 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 1, ], ], 'nextPeriod' => [ 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE' => [ 'effectiveOn' => '1472688000', 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 1, ], ], ], 'comments' => [ 'input' => [ 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about Device Farm offerings available to your account.', 'id' => 'to-get-status-information-about-device-offerings-1472568124402', 'title' => 'To get status information about device offerings', ], ], 'GetProject' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:5e01a8c7-c861-4c0a-b1d5-12345EXAMPLE', ], 'output' => [ 'project' => [ 'name' => 'My Project', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:5e01a8c7-c861-4c0a-b1d5-12345EXAMPLE', 'created' => '1472660939.152', ], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example gets information about a specific project.', 'id' => 'to-get-a-project-1470975038449', 'title' => 'To get information about a project', ], ], 'GetRemoteAccessSession' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:session:EXAMPLE-GUID-123-456', ], 'output' => [ 'remoteAccessSession' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the remote access session ARN by using the list-remote-access-sessions CLI command.', ], 'output' => [], ], 'description' => 'The following example gets a specific remote access session.', 'id' => 'to-get-a-remote-access-session-1471014119414', 'title' => 'To get a remote access session', ], ], 'GetRun' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/0fcac17b-6122-44d7-ae5a-12345EXAMPLE', ], 'output' => [ 'run' => [ 'name' => 'My Test Run', 'type' => 'BUILTIN_EXPLORER', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/0fcac17b-6122-44d7-ae5a-12345EXAMPLE', 'billingMethod' => 'METERED', 'completedJobs' => 0, 'counters' => [ 'errored' => 0, 'failed' => 0, 'passed' => 0, 'skipped' => 0, 'stopped' => 0, 'total' => 0, 'warned' => 0, ], 'created' => '1472667509.852', 'deviceMinutes' => [ 'metered' => 0.0, 'total' => 0.0, 'unmetered' => 0.0, ], 'platform' => 'ANDROID', 'result' => 'PENDING', 'status' => 'RUNNING', 'totalJobs' => 3, ], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the run ARN by using the list-runs CLI command.', ], 'output' => [], ], 'description' => 'The following example gets information about a specific test run.', 'id' => 'to-get-a-test-run-1471015895657', 'title' => 'To get information about a test run', ], ], 'GetSuite' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:suite:EXAMPLE-GUID-123-456', ], 'output' => [ 'suite' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the suite ARN by using the list-suites CLI command.', ], 'output' => [], ], 'description' => 'The following example gets information about a specific test suite.', 'id' => 'to-get-information-about-a-test-suite-1471016525008', 'title' => 'To get information about a test suite', ], ], 'GetTest' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:test:EXAMPLE-GUID-123-456', ], 'output' => [ 'test' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the test ARN by using the list-tests CLI command.', ], 'output' => [], ], 'description' => 'The following example gets information about a specific test.', 'id' => 'to-get-information-about-a-specific-test-1471025744238', 'title' => 'To get information about a specific test', ], ], 'GetUpload' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:upload:EXAMPLE-GUID-123-456', ], 'output' => [ 'upload' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the test ARN by using the list-uploads CLI command.', ], 'output' => [], ], 'description' => 'The following example gets information about a specific upload.', 'id' => 'to-get-information-about-a-specific-upload-1471025996221', 'title' => 'To get information about a specific upload', ], ], 'InstallToRemoteAccessSession' => [ [ 'input' => [ 'appArn' => 'arn:aws:devicefarm:us-west-2:123456789101:app:EXAMPLE-GUID-123-456', 'remoteAccessSessionArn' => 'arn:aws:devicefarm:us-west-2:123456789101:session:EXAMPLE-GUID-123-456', ], 'output' => [ 'appUpload' => [], ], 'comments' => [ 'input' => [ 'remoteAccessSessionArn' => 'You can get the remote access session ARN by using the list-remote-access-sessions CLI command.', ], 'output' => [], ], 'description' => 'The following example installs a specific app to a device in a specific remote access session.', 'id' => 'to-install-to-a-remote-access-session-1471634453818', 'title' => 'To install to a remote access session', ], ], 'ListArtifacts' => [ [ 'input' => [ 'type' => 'SCREENSHOT', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:EXAMPLE-GUID-123-456', ], 'comments' => [ 'input' => [ 'arn' => 'Can also be used to list artifacts for a Job, Suite, or Test ARN.', ], 'output' => [], ], 'description' => 'The following example lists screenshot artifacts for a specific run.', 'id' => 'to-list-artifacts-for-a-resource-1471635409527', 'title' => 'To list artifacts for a resource', ], ], 'ListDevicePools' => [ [ 'input' => [ 'type' => 'PRIVATE', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'output' => [ 'devicePools' => [ [ 'name' => 'Top Devices', 'arn' => 'arn:aws:devicefarm:us-west-2::devicepool:082d10e5-d7d7-48a5-ba5c-12345EXAMPLE', 'description' => 'Top devices', 'rules' => [ [ 'value' => '["arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE"]', 'attribute' => 'ARN', 'operator' => 'IN', ], ], ], [ 'name' => 'My Android Device Pool', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:devicepool:5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/bf96e75a-28f6-4e61-b6a7-12345EXAMPLE', 'description' => 'Samsung Galaxy Android devices', 'rules' => [ [ 'value' => '["arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE","arn:aws:devicefarm:us-west-2::device:123456789EXAMPLE"]', 'attribute' => 'ARN', 'operator' => 'IN', ], ], ], ], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example returns information about the private device pools in a specific project.', 'id' => 'to-get-information-about-device-pools-1471635745170', 'title' => 'To get information about device pools', ], ], 'ListDevices' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'output' => [], 'comments' => [ 'input' => [ 'arn' => 'You can get the project ARN by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example returns information about the available devices in a specific project.', 'id' => 'to-get-information-about-devices-1471641699344', 'title' => 'To get information about devices', ], ], 'ListJobs' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', ], 'comments' => [ 'input' => [ 'arn' => 'You can get the project ARN by using the list-jobs CLI command.', ], 'output' => [], ], 'description' => 'The following example returns information about jobs in a specific project.', 'id' => 'to-get-information-about-jobs-1471642228071', 'title' => 'To get information about jobs', ], ], 'ListOfferingTransactions' => [ [ 'input' => [ 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE=', ], 'output' => [ 'offeringTransactions' => [ [ 'cost' => [ 'amount' => 0, 'currencyCode' => 'USD', ], 'createdOn' => '1470021420', 'offeringStatus' => [ 'type' => 'RENEW', 'effectiveOn' => '1472688000', 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 0, ], 'transactionId' => '03728003-d1ea-4851-abd6-12345EXAMPLE', ], [ 'cost' => [ 'amount' => 250, 'currencyCode' => 'USD', ], 'createdOn' => '1470021420', 'offeringStatus' => [ 'type' => 'PURCHASE', 'effectiveOn' => '1470021420', 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 1, ], 'transactionId' => '56820b6e-06bd-473a-8ff8-12345EXAMPLE', ], [ 'cost' => [ 'amount' => 175, 'currencyCode' => 'USD', ], 'createdOn' => '1465538520', 'offeringStatus' => [ 'type' => 'PURCHASE', 'effectiveOn' => '1465538520', 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Unmetered Device Slot', 'id' => '8980F81C-00D7-469D-8EC6-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 1, ], 'transactionId' => '953ae2c6-d760-4a04-9597-12345EXAMPLE', ], [ 'cost' => [ 'amount' => 8.07, 'currencyCode' => 'USD', ], 'createdOn' => '1459344300', 'offeringStatus' => [ 'type' => 'PURCHASE', 'effectiveOn' => '1459344300', 'offering' => [ 'type' => 'RECURRING', 'description' => 'iOS Unmetered Device Slot', 'id' => 'A53D4D73-A6F6-4B82-A0B0-12345EXAMPLE', 'platform' => 'IOS', ], 'quantity' => 1, ], 'transactionId' => '2baf9021-ae3e-47f5-ab52-12345EXAMPLE', ], ], ], 'comments' => [ 'input' => [ 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about Device Farm offering transactions.', 'id' => 'to-get-information-about-device-offering-transactions-1472561712315', 'title' => 'To get information about device offering transactions', ], ], 'ListOfferings' => [ [ 'input' => [ 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE=', ], 'output' => [ 'offerings' => [ [ 'type' => 'RECURRING', 'description' => 'iOS Unmetered Device Slot', 'id' => 'A53D4D73-A6F6-4B82-A0B0-12345EXAMPLE', 'platform' => 'IOS', 'recurringCharges' => [ [ 'cost' => [ 'amount' => 250, 'currencyCode' => 'USD', ], 'frequency' => 'MONTHLY', ], ], ], [ 'type' => 'RECURRING', 'description' => 'Android Unmetered Device Slot', 'id' => '8980F81C-00D7-469D-8EC6-12345EXAMPLE', 'platform' => 'ANDROID', 'recurringCharges' => [ [ 'cost' => [ 'amount' => 250, 'currencyCode' => 'USD', ], 'frequency' => 'MONTHLY', ], ], ], [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', 'recurringCharges' => [ [ 'cost' => [ 'amount' => 250, 'currencyCode' => 'USD', ], 'frequency' => 'MONTHLY', ], ], ], [ 'type' => 'RECURRING', 'description' => 'iOS Remote Access Unmetered Device Slot', 'id' => '552B4DAD-A6C9-45C4-94FB-12345EXAMPLE', 'platform' => 'IOS', 'recurringCharges' => [ [ 'cost' => [ 'amount' => 250, 'currencyCode' => 'USD', ], 'frequency' => 'MONTHLY', ], ], ], ], ], 'comments' => [ 'input' => [ 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about available device offerings.', 'id' => 'to-get-information-about-device-offerings-1472562810999', 'title' => 'To get information about device offerings', ], ], 'ListProjects' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:7ad300ed-8183-41a7-bf94-12345EXAMPLE', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'projects' => [ [ 'name' => 'My Test Project', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:7ad300ed-8183-41a7-bf94-12345EXAMPLE', 'created' => '1453163262.105', ], [ 'name' => 'Hello World', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:d6b087d9-56db-4e44-b9ec-12345EXAMPLE', 'created' => '1470350112.439', ], ], ], 'comments' => [ 'input' => [ 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about the specified project in Device Farm.', 'id' => 'to-get-information-about-a-device-farm-project-1472564014388', 'title' => 'To get information about a Device Farm project', ], ], 'ListRemoteAccessSessions' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:session:EXAMPLE-GUID-123-456', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE=', ], 'output' => [ 'remoteAccessSessions' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the session by using the list-sessions CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about a specific Device Farm remote access session.', 'id' => 'to-get-information-about-a-remote-access-session-1472581144803', 'title' => 'To get information about a remote access session', ], ], 'ListRuns' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/0fcac17b-6122-44d7-ae5a-12345EXAMPLE', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'runs' => [ [ 'name' => 'My Test Run', 'type' => 'BUILTIN_EXPLORER', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:5e01a8c7-c861-4c0a-b1d5-5ec6e6c6dd23/0fcac17b-6122-44d7-ae5a-12345EXAMPLE', 'billingMethod' => 'METERED', 'completedJobs' => 0, 'counters' => [ 'errored' => 0, 'failed' => 0, 'passed' => 0, 'skipped' => 0, 'stopped' => 0, 'total' => 0, 'warned' => 0, ], 'created' => '1472667509.852', 'deviceMinutes' => [ 'metered' => 0.0, 'total' => 0.0, 'unmetered' => 0.0, ], 'platform' => 'ANDROID', 'result' => 'PENDING', 'status' => 'RUNNING', 'totalJobs' => 3, ], ], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the run by using the list-runs CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about a specific test run.', 'id' => 'to-get-information-about-test-runs-1472582711069', 'title' => 'To get information about a test run', ], ], 'ListSamples' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'samples' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the project by using the list-projects CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about samples, given a specific Device Farm project.', 'id' => 'to-get-information-about-samples-1472582847534', 'title' => 'To get information about samples', ], ], 'ListSuites' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:job:EXAMPLE-GUID-123-456', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'suites' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the job by using the list-jobs CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about suites, given a specific Device Farm job.', 'id' => 'to-get-information-about-suites-1472583038218', 'title' => 'To get information about suites', ], ], 'ListTests' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'tests' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the project by using the list-projects CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about tests, given a specific Device Farm project.', 'id' => 'to-get-information-about-tests-1472617372212', 'title' => 'To get information about tests', ], ], 'ListUniqueProblems' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'uniqueProblems' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the project by using the list-projects CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about unique problems, given a specific Device Farm project.', 'id' => 'to-get-information-about-unique-problems-1472617781008', 'title' => 'To get information about unique problems', ], ], 'ListUploads' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', 'nextToken' => 'RW5DdDJkMWYwZjM2MzM2VHVpOHJIUXlDUXlhc2QzRGViYnc9SEXAMPLE', ], 'output' => [ 'uploads' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the project by using the list-projects CLI command.', 'nextToken' => 'A dynamically generated value, used for paginating results.', ], 'output' => [], ], 'description' => 'The following example returns information about uploads, given a specific Device Farm project.', 'id' => 'to-get-information-about-uploads-1472617943090', 'title' => 'To get information about uploads', ], ], 'PurchaseOffering' => [ [ 'input' => [ 'offeringId' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'quantity' => 1, ], 'output' => [ 'offeringTransaction' => [ 'cost' => [ 'amount' => 8.07, 'currencyCode' => 'USD', ], 'createdOn' => '1472648340', 'offeringStatus' => [ 'type' => 'PURCHASE', 'effectiveOn' => '1472648340', 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 1, ], 'transactionId' => 'd30614ed-1b03-404c-9893-12345EXAMPLE', ], ], 'comments' => [ 'input' => [ 'offeringId' => 'You can get the offering ID by using the list-offerings CLI command.', ], 'output' => [], ], 'description' => 'The following example purchases a specific device slot offering.', 'id' => 'to-purchase-a-device-slot-offering-1472648146343', 'title' => 'To purchase a device slot offering', ], ], 'RenewOffering' => [ [ 'input' => [ 'offeringId' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'quantity' => 1, ], 'output' => [ 'offeringTransaction' => [ 'cost' => [ 'amount' => 250, 'currencyCode' => 'USD', ], 'createdOn' => '1472648880', 'offeringStatus' => [ 'type' => 'RENEW', 'effectiveOn' => '1472688000', 'offering' => [ 'type' => 'RECURRING', 'description' => 'Android Remote Access Unmetered Device Slot', 'id' => 'D68B3C05-1BA6-4360-BC69-12345EXAMPLE', 'platform' => 'ANDROID', ], 'quantity' => 1, ], 'transactionId' => 'e90f1405-8c35-4561-be43-12345EXAMPLE', ], ], 'comments' => [ 'input' => [ 'offeringId' => 'You can get the offering ID by using the list-offerings CLI command.', ], 'output' => [], ], 'description' => 'The following example renews a specific device slot offering.', 'id' => 'to-renew-a-device-slot-offering-1472648899785', 'title' => 'To renew a device slot offering', ], ], 'ScheduleRun' => [ [ 'input' => [ 'name' => 'MyRun', 'devicePoolArn' => 'arn:aws:devicefarm:us-west-2:123456789101:pool:EXAMPLE-GUID-123-456', 'projectArn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:EXAMPLE-GUID-123-456', 'test' => [ 'type' => 'APPIUM_JAVA_JUNIT', 'testPackageArn' => 'arn:aws:devicefarm:us-west-2:123456789101:test:EXAMPLE-GUID-123-456', ], ], 'output' => [ 'run' => [], ], 'comments' => [ 'input' => [ 'devicePoolArn' => 'You can get the Amazon Resource Name (ARN) of the device pool by using the list-pools CLI command.', 'projectArn' => 'You can get the Amazon Resource Name (ARN) of the project by using the list-projects CLI command.', 'testPackageArn' => 'You can get the Amazon Resource Name (ARN) of the test package by using the list-tests CLI command.', ], 'output' => [], ], 'description' => 'The following example schedules a test run named MyRun.', 'id' => 'to-schedule-a-test-run-1472652429636', 'title' => 'To schedule a test run', ], ], 'StopRun' => [ [ 'input' => [ 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:run:EXAMPLE-GUID-123-456', ], 'output' => [ 'run' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the test run by using the list-runs CLI command.', ], 'output' => [], ], 'description' => 'The following example stops a specific test run.', 'id' => 'to-stop-a-test-run-1472653770340', 'title' => 'To stop a test run', ], ], 'UpdateDevicePool' => [ [ 'input' => [ 'name' => 'NewName', 'arn' => 'arn:aws:devicefarm:us-west-2::devicepool:082d10e5-d7d7-48a5-ba5c-12345EXAMPLE', 'description' => 'NewDescription', 'rules' => [ [ 'value' => 'True', 'attribute' => 'REMOTE_ACCESS_ENABLED', 'operator' => 'EQUALS', ], ], ], 'output' => [ 'devicePool' => [], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the device pool by using the list-pools CLI command.', ], 'output' => [ 'devicePool' => 'Note: you cannot update curated device pools.', ], ], 'description' => 'The following example updates the specified device pool with a new name and description. It also enables remote access of devices in the device pool.', 'id' => 'to-update-a-device-pool-1472653887677', 'title' => 'To update a device pool', ], ], 'UpdateProject' => [ [ 'input' => [ 'name' => 'NewName', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:8f75187d-101e-4625-accc-12345EXAMPLE', ], 'output' => [ 'project' => [ 'name' => 'NewName', 'arn' => 'arn:aws:devicefarm:us-west-2:123456789101:project:8f75187d-101e-4625-accc-12345EXAMPLE', 'created' => '1448400709.927', ], ], 'comments' => [ 'input' => [ 'arn' => 'You can get the Amazon Resource Name (ARN) of the project by using the list-projects CLI command.', ], 'output' => [], ], 'description' => 'The following example updates the specified project with a new name.', 'id' => 'to-update-a-device-pool-1472653887677', 'title' => 'To update a device pool', ], ], ],];
