#!/bin/sh
if [ $# -gt 0 ]; then
	output=$1
else
	output=print_status.html
fi
dir=`dirname $0`
/usr/bin/env php $dir/generator.php > $output.new
mv $output.new $output
