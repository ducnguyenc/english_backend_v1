FROM docker.elastic.co/elasticsearch/elasticsearch:7.8.0
RUN elasticsearch-plugin install analysis-icu && elasticsearch-plugin install analysis-kuromoji