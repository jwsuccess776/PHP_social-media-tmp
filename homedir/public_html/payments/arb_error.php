<?php

switch ($code) {
	case 'E00001':
		$error_text="An error occurred during processing. Please try again.";
		$description="An unexpected system error occurred while processing this request.";
		break;	
	case 'E00002':
		$error_text="The content-type specified is not supported.";
		$description="The only supported content-types are text/xml and application/xml.";
		break;
	case 'E00003':
		$error_text="An error occurred while parsing the XML request.";
		$description="This is the result of an XML parser error.";
		break;
	case 'E00004':
		$error_text="The name of the requested API method is invalid.";
		$description="The name of the root node of the XML request is the API method being called. It is not valid.";
		break;	
	case 'E00005':
		$error_text="The merchantAuthentication.transactionKey is invalid or not present.";
		$description="Merchant authentication requires a valid value for transaction key.";
		break;	
	case 'E00006':
		$error_text="The merchantAuthentication.name is invalid or not present.";
		$description="Merchant authentication requires a valid value for name.";
		break;	
	case 'E00007':
		$error_text="User authentication failed due to invalid authentication values.";
		$description="The name/and or transaction key is invalid. ";
		break;	
	case 'E00008':
		$error_text="User authentication failed. The payment gateway account or user is inactive.";
		$description="The payment gateway or user account is not currently active.";
		break;	
	case 'E00009':
		$error_text="The payment gateway account is in Test Mode. The request cannot be processed.";
		$description="The requested API method cannot be executed while the payment gateway account is in Test Mode.";
		break;	
	case 'E00010':
		$error_text="User authentication failed. You do not have the appropriate permissions.";
		$description="The user does not have permission to call the API.";
		break;	
	case 'E00011':
		$error_text="Access denied. You do not have the appropriate permissions.";
		$description="The user does not have permission to call the API method.";
		break;	
	case 'E00012':
		$error_text="A duplicate subscription already exists.";
		$description="A duplicate of the subscription was already submitted. The duplicate check looks at several fields including payment information, billing information and, specifically for subscriptions, Start Date, Interval and Unit.";
		break;	
	case 'E00013':
		$error_text="The field is invalid.";
		$description="One of the field values is not valid.";
		break;	
	case 'E00014':
		$error_text="A required field is not present.";
		$description="One of the required fields was not present.";
		break;	
	case 'E00015':
		$error_text="The field length is invalid.";
		$description="One of the fields has an invalid length.";
		break;	
	case 'E00016':
		$error_text="The field type is invalid.";
		$description="The field type is not valid.";
		break;	
	case 'E00017':
		$error_text="The startDate cannot occur in the past.";
		$description="The subscription start date cannot occur before the subscription submission date. (Note: validation is performed against local server date, which is Mountain Time.)";
		break;	
	case 'E00018':
		$error_text="The credit card expires before the subscription startDate.";
		$description="The credit card is not valid as of the start date of the subscription.";
		break;	
	case 'E00019':
		$error_text="The customer taxId or driversLicense information is required.";
		$description="The customer tax ID or driver's license information (driver's license number, driver's license state, driver's license DOB) is required for the subscription.";
		break;	
	case 'E00020':
		$error_text="The payment gateway account is not enabled for eCheck.Net subscriptions.";
		$description="This payment gateway account is not set up to process eCheck.Net subscriptions.";
		break;	
	case 'E00021':
		$error_text="The payment gateway account is not enabled for credit card subscriptions.";
		$description="This payment gateway account is not set up to process credit card subscriptions.";
		break;	
	case 'E00022':
		$error_text="The interval length cannot exceed 365 days or 12 months.";
		$description="The interval length must be 7 to 365 days or 1 to 12 months.";
		break;	
	case 'E00024':
		$error_text="The trialOccurrences is required when trialAmount is specified.";
		$description="The number of trial occurrences cannot be zero if a valid trial amount is submitted.";
		break;	
	case 'E00025':
		$error_text="Automated Recurring Billing is not enabled.";
		$description="The payment gateway account is not enabled for Automated Recurring Billing.";
		break;	
	case 'E00026':
		$error_text="Both trialAmount and trialOccurrences are required.";
		$description="If either a trial amount or number of trial occurrences is specified then values for both must be submitted.";
		break;	
	case 'E00027':
		$error_text="The test transaction was unsuccessful.";
		$description="An approval was not returned for the test transaction.";
		break;	
	case 'E00028':
		$error_text="The trialOccurrences must be less than totalOccurrences.";
		$description="The number of trial occurrences specified must be less than the number of total occurrences specified.";
		break;	
	case 'E00029':
		$error_text="Payment information is required.";
		$description="Payment information is required when creating a subscription or payment profile.";
		break;	
	case 'E00030':
		$error_text="A paymentSchedule is required.";
		$description="A payment schedule is required when creating a subscription.";
		break;	
	case 'E00031':
		$error_text="The amount is required.";
		$description="The subscription amount is required when creating a subscription.";
		break;	
	case 'E00032':
		$error_text="The startDate is required.";
		$description="The subscription start date is required to create a subscription.";
		break;	
	case 'E00033':
		$error_text="The subscription Start Date cannot be changed.";
		$description="Once a subscription is created the Start Date cannot be changed.";
		break;	
	case 'E00034':
		$error_text="The interval information cannot be changed.";
		$description="Once a subscription is created the subscription interval cannot be changed.";
		break;	
	case 'E00035':
		$error_text="The subscription cannot be found.";
		$description="The subscription ID for this request is not valid for this merchant.";
		break;	
	case 'E00036':
		$error_text="The payment type cannot be changed.";
		$description="Changing the subscription payment type between credit card and eCheck.Net is not currently supported.";
		break;	
	case 'E00037':
		$error_text="The subscription cannot be updated.";
		$description="Subscriptions that are expired, canceled or terminated cannot be updated.";
		break;	
	case 'E00038':
		$error_text="The subscription cannot be canceled.";
		$description="Subscriptions that are expired or terminated cannot be canceled.";
		break;	
	case 'E00045':
		$error_text="The root node does not reference a valid XML namespace.";
		$description="An error exists in the XML namespace. This error is similar to E00003.";
		break;	
	case 'I00001':
		$error_text="Successful";
		$description="The request was processed successfully."; 
		break;
}

$error_message=$error_text."<br><br>".$description;
?>