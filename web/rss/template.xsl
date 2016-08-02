<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" omit-xml-declaration="yes"/>
    <xsl:template match="/">
        <html>
            <head>
                <title><xsl:for-each select="rss/channel"><xsl:value-of select="title"/></xsl:for-each></title>
                <link rel="stylesheet" type="text/css" href="../rss/rss.css" />
            </head>
            <body>
                <div class="header">
                    <xsl:for-each select="rss/channel">
                        <h1><xsl:value-of select="title"/></h1>
                        <p>
							This is an RSS feed from the Crossover News website. RSS feeds allow you to stay up to date with the latest news and features you want from Crossover News.
						</p>
						<p>
							To subscribe to it, you will need a News Reader or other similar device. If you would like to use this feed to display Crossover News content on your site.
						</p>
                    </xsl:for-each>
                </div>
                <div class="body">
                    <dl>                        
                    <xsl:for-each select="rss/channel/item">
                            <dt>
                                <span class="title">
                                    <a> 
                                        <xsl:attribute name="href">
                                            <xsl:value-of select="link"/>
                                        </xsl:attribute> 
                                        <xsl:value-of select="title"/>
                                    </a>
                                </span>
                                <span class="time"><xsl:value-of select="pubDate"/></span>
                            </dt>
                            <dd>
                            	<p>    
	                            	<xsl:element name="img">
									  <xsl:attribute name="src">
									    <xsl:value-of select="urlImage"/>
									  </xsl:attribute>
									</xsl:element>
								</p>
                                <p><xsl:value-of select="description"/></p>
                            </dd>
                    </xsl:for-each>
                    </dl>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>