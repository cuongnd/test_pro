XML Merge - A script to constructively merge two XML files

This is a script written in php which will allow one to constructively
merge two XML files i.e. the fields common in both the XML files will be
updated with the values from the old XML file into new XML file. But the
fields which are not available in old XML file but are present in new 
XML file will remain intact thus merging both the old and new XML files
constructively.

Many a times while working with XML files we come across a need for a 
script which will merge two XML files constructively and so I thought
of creating such a script in PHP which will enable merging of two 
XML files into one XML file. 

Before executing, the New XML, Old XML & Schema XSD file fields needs to be
updated in the script file as per your need. Currently, the script doesn't
support passing of XML file names as command line argument.

The script can be executed by command "php xml-merge.php"

This script is released under The MIT License (MIT)

Copyright (c) 2012 Samarth Parikh (samarthp@ymail.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
