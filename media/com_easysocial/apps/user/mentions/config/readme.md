# Configurations folder.
All of your .json configuration files must be placed within this folder. The format of the files should be in `json` notation.

## layouts.json
Stores the available layouts for this application.

### Example `layouts.json`
	{
		"dashboard" :
		{
			// Embedded views would be displayed on the page using an ajax call.
			"view"	: "embed"
		},
		"profile"	:
		{
			// Canvas views are loaded on a page of it's own.
			"view"	: "canvas"
		}
	}
