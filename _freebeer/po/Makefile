# $CVSHeader: _freebeer/po/Makefile,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

# Makefile to generate .mo files from .po files

APPLICATION = freebeer
MSGFMT = msgfmt --statistics -c -v -o
MSGFMTSOL = msgfmt -v -o

all: install

install: *.po
	@echo "Checking for os ... ${OSTYPE}"; \
	if test "${OSTYPE}" = "solaris"; then \
		echo "You'll probably get some warnings on Solaris. This is normal."; \
	fi; \
	for LOCALE in `ls *.po | sed 's/\.[^.]*$$//g'`; do \
		if test "$${LOCALE}.po" = "messages.po" -o "$${LOCALE}.po" = "compendium.po"; then \
			continue; \
		fi; \
		echo "Compiling locale $${LOCALE}:"; \
		if ./shtool mkdir -p ../locale/$${LOCALE}/LC_MESSAGES; then \
			if test "${OSTYPE}" = "solaris"; then \
				if ${MSGFMTSOL} ../locale/$${LOCALE}/LC_MESSAGES/${APPLICATION}.mo $${LOCALE}.po; then \
					echo "  ... done"; \
					echo; \
				else \
					echo "  ... FAILED"; \
					echo; \
				fi \
			else \
				if ${MSGFMT} ../locale/$${LOCALE}/LC_MESSAGES/${APPLICATION}.mo $${LOCALE}.po; then \
					echo "  ... done"; \
					echo; \
				else \
					echo "  ... FAILED"; \
					echo; \
				fi \
			fi; \
		else \
			echo "Could not create locale directory for $${LOCALE}."; \
		fi \
	done;
