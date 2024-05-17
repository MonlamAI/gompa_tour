<?php
// This file was auto-generated from sdk-root/src/data/iot1click-devices/2018-05-14/docs-2.json
return [ 'version' => '2.0', 'service' => '<p>Describes all of the AWS IoT 1-Click device-related API operations for the service. Also provides sample requests, responses, and errors for the supported web services protocols.</p>', 'operations' => [ 'ClaimDevicesByClaimCode' => '<p>Adds device(s) to your account (i.e., claim one or more devices) if and only if you received a claim code with the device(s).</p>', 'DescribeDevice' => '<p>Given a device ID, returns a DescribeDeviceResponse object describing the details of the device.</p>', 'FinalizeDeviceClaim' => '<p>Given a device ID, finalizes the claim request for the associated device.</p><note> <p>Claiming a device consists of initiating a claim, then publishing a device event, and finalizing the claim. For a device of type button, a device event can be published by simply clicking the device.</p> </note>', 'GetDeviceMethods' => '<p>Given a device ID, returns the invokable methods associated with the device.</p>', 'InitiateDeviceClaim' => '<p>Given a device ID, initiates a claim request for the associated device.</p><note> <p>Claiming a device consists of initiating a claim, then publishing a device event, and finalizing the claim. For a device of type button, a device event can be published by simply clicking the device.</p> </note>', 'InvokeDeviceMethod' => '<p>Given a device ID, issues a request to invoke a named device method (with possible parameters). See the "Example POST" code snippet below.</p>', 'ListDeviceEvents' => '<p>Using a device ID, returns a DeviceEventsResponse object containing an array of events for the device.</p>', 'ListDevices' => '<p>Lists the 1-Click compatible devices associated with your AWS account.</p>', 'ListTagsForResource' => '<p>Lists the tags associated with the specified resource ARN.</p>', 'TagResource' => '<p>Adds or updates the tags associated with the resource ARN. See <a href="https://docs.aws.amazon.com/iot-1-click/latest/developerguide/1click-appendix.html#1click-limits">AWS IoT 1-Click Service Limits</a> for the maximum number of tags allowed per resource.</p>', 'UnclaimDevice' => '<p>Disassociates a device from your AWS account using its device ID.</p>', 'UntagResource' => '<p>Using tag keys, deletes the tags (key/value pairs) associated with the specified resource ARN.</p>', 'UpdateDeviceState' => '<p>Using a Boolean value (true or false), this operation enables or disables the device given a device ID.</p>', ], 'shapes' => [ 'Attributes' => [ 'base' => NULL, 'refs' => [ 'Device$Attributes' => '<p>The user specified attributes associated with the device for an event.</p>', ], ], 'ClaimDevicesByClaimCodeResponse' => [ 'base' => NULL, 'refs' => [], ], 'DescribeDeviceResponse' => [ 'base' => NULL, 'refs' => [], ], 'Device' => [ 'base' => NULL, 'refs' => [ 'DeviceEvent$Device' => '<p>An object representing the device associated with the event.</p>', ], ], 'DeviceAttributes' => [ 'base' => '<p> DeviceAttributes is a string-to-string map specified by the user.</p>', 'refs' => [ 'DeviceDescription$Attributes' => '<p>An array of zero or more elements of DeviceAttribute objects providing user specified device attributes.</p>', ], ], 'DeviceClaimResponse' => [ 'base' => NULL, 'refs' => [], ], 'DeviceDescription' => [ 'base' => NULL, 'refs' => [ 'DescribeDeviceResponse$DeviceDescription' => '<p>Device details.</p>', '__listOfDeviceDescription$member' => NULL, ], ], 'DeviceEvent' => [ 'base' => NULL, 'refs' => [ '__listOfDeviceEvent$member' => NULL, ], ], 'DeviceEventsResponse' => [ 'base' => NULL, 'refs' => [], ], 'DeviceMethod' => [ 'base' => NULL, 'refs' => [ 'InvokeDeviceMethodRequest$DeviceMethod' => '<p>The device method to invoke.</p>', '__listOfDeviceMethod$member' => NULL, ], ], 'Empty' => [ 'base' => '<p>On success, an empty object is returned.</p>', 'refs' => [], ], 'ForbiddenException' => [ 'base' => NULL, 'refs' => [], ], 'GetDeviceMethodsResponse' => [ 'base' => NULL, 'refs' => [], ], 'InternalFailureException' => [ 'base' => NULL, 'refs' => [], ], 'InvalidRequestException' => [ 'base' => NULL, 'refs' => [], ], 'InvokeDeviceMethodRequest' => [ 'base' => NULL, 'refs' => [], ], 'InvokeDeviceMethodResponse' => [ 'base' => NULL, 'refs' => [], ], 'ListDevicesResponse' => [ 'base' => NULL, 'refs' => [], ], 'PreconditionFailedException' => [ 'base' => NULL, 'refs' => [], ], 'RangeNotSatisfiableException' => [ 'base' => NULL, 'refs' => [], ], 'ResourceConflictException' => [ 'base' => NULL, 'refs' => [], ], 'ResourceNotFoundException' => [ 'base' => NULL, 'refs' => [], ], 'UpdateDeviceStateRequest' => [ 'base' => NULL, 'refs' => [], ], '__boolean' => [ 'base' => NULL, 'refs' => [ 'DeviceDescription$Enabled' => '<p>A Boolean value indicating whether or not the device is enabled.</p>', 'UpdateDeviceStateRequest$Enabled' => '<p>If true, the device is enabled. If false, the device is disabled.</p>', ], ], '__doubleMin0Max100' => [ 'base' => NULL, 'refs' => [ 'DeviceDescription$RemainingLife' => '<p>A value between 0 and 1 inclusive, representing the fraction of life remaining for the device.</p>', ], ], '__integer' => [ 'base' => NULL, 'refs' => [ 'ClaimDevicesByClaimCodeResponse$Total' => '<p>The total number of devices associated with the claim code that has been processed in the claim request.</p>', ], ], '__listOfDeviceDescription' => [ 'base' => NULL, 'refs' => [ 'ListDevicesResponse$Devices' => '<p>A list of devices.</p>', ], ], '__listOfDeviceEvent' => [ 'base' => NULL, 'refs' => [ 'DeviceEventsResponse$Events' => '<p>An array of zero or more elements describing the event(s) associated with the device.</p>', ], ], '__listOfDeviceMethod' => [ 'base' => NULL, 'refs' => [ 'GetDeviceMethodsResponse$DeviceMethods' => '<p>List of available device APIs.</p>', ], ], '__string' => [ 'base' => NULL, 'refs' => [ 'Device$DeviceId' => '<p>The unique identifier of the device.</p>', 'Device$Type' => '<p>The device type, such as "button".</p>', 'DeviceAttributes$member' => NULL, 'DeviceClaimResponse$State' => '<p>The device\'s final claim state.</p>', 'DeviceDescription$Arn' => '<p>The ARN of the device.</p>', 'DeviceDescription$DeviceId' => '<p>The unique identifier of the device.</p>', 'DeviceDescription$Type' => '<p>The type of the device, such as "button".</p>', 'DeviceEvent$StdEvent' => '<p>A serialized JSON object representing the device-type specific event.</p>', 'DeviceEventsResponse$NextToken' => '<p>The token to retrieve the next set of results.</p>', 'DeviceMethod$DeviceType' => '<p>The type of the device, such as "button".</p>', 'DeviceMethod$MethodName' => '<p>The name of the method applicable to the deviceType.</p>', 'ForbiddenException$Code' => '<p>403</p>', 'ForbiddenException$Message' => '<p>The 403 error message returned by the web server.</p>', 'InternalFailureException$Code' => '<p>500</p>', 'InternalFailureException$Message' => '<p>The 500 error message returned by the web server.</p>', 'InvalidRequestException$Code' => '<p>400</p>', 'InvalidRequestException$Message' => '<p>The 400 error message returned by the web server.</p>', 'InvokeDeviceMethodRequest$DeviceMethodParameters' => '<p>A JSON encoded string containing the device method request parameters.</p>', 'InvokeDeviceMethodResponse$DeviceMethodResponse' => '<p>A JSON encoded string containing the device method response.</p>', 'ListDevicesResponse$NextToken' => '<p>The token to retrieve the next set of results.</p>', 'PreconditionFailedException$Code' => '<p>412</p>', 'PreconditionFailedException$Message' => '<p>An error message explaining the error or its remedy.</p>', 'RangeNotSatisfiableException$Code' => '<p>416</p>', 'RangeNotSatisfiableException$Message' => '<p>The requested number of results specified by nextToken cannot be satisfied.</p>', 'ResourceConflictException$Code' => '<p>409</p>', 'ResourceConflictException$Message' => '<p>An error message explaining the error or its remedy.</p>', 'ResourceNotFoundException$Code' => '<p>404</p>', 'ResourceNotFoundException$Message' => '<p>The requested device could not be found.</p>', ], ], '__stringMin12Max40' => [ 'base' => NULL, 'refs' => [ 'ClaimDevicesByClaimCodeResponse$ClaimCode' => '<p>The claim code provided by the device manufacturer.</p>', ], ], ],];