#! /bin/sh

# run it 4 times, just to make sure ;-)
tex2page ../reference.tex
tex2page ../reference.tex
tex2page ../reference.tex
tex2page ../reference.tex

# perl -pi -e 's/\`\`/&#x201C;/g' reference.html
# perl -pi -e "s/''/&#x201D;/g" reference.html
